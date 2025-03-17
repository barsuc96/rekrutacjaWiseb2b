import express from 'express';

import cms from './Cms/index.js';

const router = express.Router();

const allowCrossDomain = (req, res, next) => {
    res.header('Access-Control-Allow-Credentials', true);
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET, PUT, POST, PATCH, DELETE, OPTIONS');
    res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    next();
};

// Cms
router.use('/cms', cms);

export default {
    allowCrossDomain,
    router,
}