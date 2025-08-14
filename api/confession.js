// api/confessions.js - Handle confessions/reflections
export default function handler(req, res) {
    // Enable CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        res.status(200).end();
        return;
    }

    if (req.method === 'GET') {
        // Get confessions from cookie
        const cookies = req.headers.cookie || '';
        const confessionsCookie = cookies
            .split(';')
            .find(cookie => cookie.trim().startsWith('confessions='));

        let confessions = [];
        
        if (confessionsCookie) {
            try {
                const encoded = confessionsCookie.split('=')[1];
                const decoded = decodeURIComponent(encoded);
                confessions = JSON.parse(decoded);
            } catch (error) {
                console.error('Error parsing confessions cookie:', error);
                confessions = [];
            }
        }

        res.status(200).json({ 
            confessions: confessions,
            message: 'Your regrets have been retrieved...' 
        });
        return;
    }

    if (req.method === 'POST') {
        const { reflection } = req.body;

        if (!reflection || typeof reflection !== 'string' || reflection.trim() === '') {
            res.status(400).json({ error: 'Reflection is required and must be a non-empty string' });
            return;
        }

        // Get existing confessions
        const cookies = req.headers.cookie || '';
        const confessionsCookie = cookies
            .split(';')
            .find(cookie => cookie.trim().startsWith('confessions='));

        let confessions = [];
        
        if (confessionsCookie) {
            try {
                const encoded = confessionsCookie.split('=')[1];
                const decoded = decodeURIComponent(encoded);
                confessions = JSON.parse(decoded);
            } catch (error) {
                console.error('Error parsing existing confessions:', error);
                confessions = [];
            }
        }

        // Add new confession
        const newConfession = {
            date: new Date().toISOString(),
            reflection: reflection.trim().substring(0, 1000) // Limit length
        };

        confessions.push(newConfession);

        // Keep only last 50 confessions to prevent cookie overflow
        if (confessions.length > 50) {
            confessions = confessions.slice(-50);
        }

        try {
            // Save to cookie
            const encoded = encodeURIComponent(JSON.stringify(confessions));
            
            // Check if cookie size is reasonable (cookies have ~4KB limit)
            if (encoded.length > 3500) {
                // Remove oldest confessions if too large
                confessions = confessions.slice(-25);
                const newEncoded = encodeURIComponent(JSON.stringify(confessions));
                
                res.setHeader('Set-Cookie', [
                    `confessions=${newEncoded}; Max-Age=31536000; Path=/; SameSite=Lax`
                ]);
            } else {
                res.setHeader('Set-Cookie', [
                    `confessions=${encoded}; Max-Age=31536000; Path=/; SameSite=Lax`
                ]);
            }

            res.status(201).json({ 
                message: 'Your regret has been recorded...',
                confession: newConfession,
                totalConfessions: confessions.length
            });
        } catch (error) {
            console.error('Error saving confession:', error);
            res.status(500).json({ error: 'Failed to save your regret' });
        }
        return;
    }

    res.status(405).json({ error: 'Method not allowed' });
}