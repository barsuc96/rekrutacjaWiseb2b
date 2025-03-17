import express from 'express';
import bodyParser from 'body-parser';
import cors from 'cors';

import route from './routes/index.js';

import error404 from './utils/404.json' assert { type: "json" }

const app = express();

// parse application/json  
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(route.allowCrossDomain);
app.use(cors());

app.use('/api/ui', route.router);

app.use(function (req, res) {
    if (req.accepts('json')) {
        res.status(404).json(error404);
        return;
    }
});

app.listen(5000, () => {
    console.log('Our app is listening for request on port 5000');
});