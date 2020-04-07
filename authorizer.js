'use strict';

module.exports.auth = (event, context, callback) => {
    let Authorization = event.headers.Authorization;
    if (!Authorization) return callback('Unauthorized');
    let [username, password] = (new Buffer(Authorization.split(' ')[1], 'base64')).toString().split(':');
    if (username === 'hoge' && password === 'fuga') {
        callback(null, {
            principalId: 'user',
            policyDocument: {
                Version: '2012-10-17',
                Statement: [{
                    Action: 'execute-api:Invoke',
                    Effect: 'Allow',
                    Resource: "*",
                }]
            }
        });
    } else {
        callback('Unauthorized');
    }
};
