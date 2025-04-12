const net = require('net');
const http = require('http');
const crypto = require('crypto');

// Track connected users
const registeredSockets = new Map();

// --- WebSocket Handshake Function ---
function createHandshakeHeaders(request) {
    const headers = {};
    const lines = request.split('\r\n');

    lines.forEach(line => {
        const parts = line.split(':');
        if (parts[0] === 'Sec-WebSocket-Key') {
            const key = parts[1].trim();
            const accept = crypto.createHash('sha1')
                .update(key + '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
                .digest('base64');
            headers['Sec-WebSocket-Accept'] = accept;
        }
    });

    return headers;
}

// --- Decode WebSocket Message ---
function decodeWebSocketMessage(buffer) {
    const length = buffer[1] & 127;
    const mask = buffer.slice(2, 6);
    const payload = buffer.slice(6, 6 + length);
    let message = '';

    for (let i = 0; i < payload.length; i++) {
        message += String.fromCharCode(payload[i] ^ mask[i % 4]);
    }

    return message;
}

// --- Encode WebSocket Message ---
function sendWebSocketMessage(socket, message) {
    const length = message.length;
    const frame = Buffer.alloc(2 + length);
    frame[0] = 0x81;
    frame[1] = length;

    for (let i = 0; i < length; i++) {
        frame[2 + i] = message.charCodeAt(i);
    }

    socket.write(frame);
}

// --- WebSocket Server ---
const wsServer = net.createServer(socket => {
    let isHandshakeDone = false;
    let buffer = '';

    socket.on('data', data => {
        buffer += data.toString();

        // Perform handshake
        if (!isHandshakeDone && buffer.includes('Sec-WebSocket-Key')) {
            const headers = createHandshakeHeaders(buffer);
            const response = [
                'HTTP/1.1 101 Switching Protocols',
                'Upgrade: websocket',
                'Connection: Upgrade',
                `Sec-WebSocket-Accept: ${headers['Sec-WebSocket-Accept']}`,
                '\r\n'
            ].join('\r\n');

            socket.write(response);
            isHandshakeDone = true;
            buffer = '';
            console.log('WebSocket handshake completed.');
            return;
        }

        if (isHandshakeDone) {
            const message = decodeWebSocketMessage(data);

            try {
                const parsed = JSON.parse(message);
                if (parsed.type === 'register' && parsed.userId) {
                    registeredSockets.set(parsed.userId, socket);
                    console.log(`User ${parsed.userId} registered.`);
                }
            } catch (err) {
                console.error('Failed to parse message:', message);
            }
        }
    });

    socket.on('end', () => {
        console.log('Connection closed');
        // Optional: Clean up disconnected sockets
        for (const [userId, s] of registeredSockets.entries()) {
            if (s === socket) {
                registeredSockets.delete(userId);
                break;
            }
        }
    });
});

wsServer.listen(8080, () => {
    console.log('WebSocket server listening on port 8080');
});

// --- HTTP Notification Server ---
const httpServer = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/notify') {
        let body = '';

        req.on('data', chunk => {
            body += chunk.toString();
        });

        req.on('end', () => {
            try {
                const data = JSON.parse(body);
                const { patientId, date, docName, specialization } = data;


                const socket = registeredSockets.get(patientId);
                if (socket) {
                    const message = `Your appointment with ${docName} (${specialization}) on ${date} has been rescheduled.`;
                    sendWebSocketMessage(socket, message);
                    console.log(`Sent notification to patient ${patientId}: ${message}`);
                } else {
                    console.log(`Patient ${patientId} not connected`);
                }

                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ status: 'ok' }));
            } catch (err) {
                res.writeHead(400);
                res.end(JSON.stringify({ error: 'Invalid JSON' }));
            }
        });
    } else {
        res.writeHead(404);
        res.end();
    }
});

httpServer.listen(3000, () => {
    console.log('HTTP server listening on port 3000');
});
