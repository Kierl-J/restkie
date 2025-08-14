// api/time.js - Get or set start time
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
        // Get start time from cookie or create new one
        const cookies = req.headers.cookie || '';
        const startTimeCookie = cookies
            .split(';')
            .find(cookie => cookie.trim().startsWith('deathCountdownStart='));

        let startTime;
        
        if (startTimeCookie) {
            startTime = startTimeCookie.split('=')[1];
        } else {
            startTime = new Date().toISOString();
            // Set cookie for 1 year
            res.setHeader('Set-Cookie', [
                `deathCountdownStart=${startTime}; Max-Age=31536000; Path=/; SameSite=Lax`
            ]);
        }

        res.status(200).json({ 
            startTime: startTime,
            message: 'Your countdown has begun...' 
        });
        return;
    }

    if (req.method === 'POST') {
        // Allow resetting start time (for testing)
        const newStartTime = new Date().toISOString();
        
        res.setHeader('Set-Cookie', [
            `deathCountdownStart=${newStartTime}; Max-Age=31536000; Path=/; SameSite=Lax`
        ]);

        res.status(200).json({ 
            startTime: newStartTime,
            message: 'Countdown reset. Your death timer starts now.' 
        });
        return;
    }

    res.status(405).json({ error: 'Method not allowed' });
}