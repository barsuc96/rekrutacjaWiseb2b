import express from 'express';

import getSections from './data/GET_Cms-sections.json' assert { type: "json" };
import postSection from './data/POST_Cms-section.json' assert { type: "json" };
import getSection from './data/GET_Cms-section-{section_id}.json' assert { type: "json" };
import getSectionFields from './data/GET_Cms-section-{section_id}-fields.json' assert { type: "json" };
import getSectionField from './data/GET_Cms-section-{section_id}-fields-{field_id}.json' assert { type: "json" };
import getSectionFieldTypes from './data/GET_Cms-section-field-types.json' assert { type: "json" };
import postSectionField from './data/POST_Cms-section-{section_id}-fields.json' assert { type: "json" };
import putSectionField from './data/PUT_Cms-section-{section_id}-fields-{field_id}.json' assert { type: "json" };
import postArticle from './data/POST_Cms-article.json' assert { type: "json" };
import getArticles from './data/GET_Cms-articles.json' assert { type: "json" };
import getArticle from './data/GET_Cms-article.json' assert { type: "json" };
import articleCountries from './data/GET_Cms-article-countries.json' assert { type: "json" };
import cmsMedia from './data/GET_cms-media.json' assert { type: "json" };
import cmsMediaId from './data/GET_cms-media-{id}.json' assert { type: "json" };

const router = express.Router();

// /api/cms/section
router.get('/section', (req, res, next) => {
    res.status(200).json(getSections);
});

// /api/cms/section
router.post('/section', (req, res, next) => {
    res.status(200).json(postSection);
});

// /api/cms/section/field-types
router.get('/section/field-types', (req, res, next) => {
    res.status(200).json(getSectionFieldTypes);
});

// /api/cms/section/{section_id}
router.get('/section/:section_id', (req, res, next) => {
    res.status(200).json(getSection);
});

// /api/cms/section/{section_id}/fields
router.get('/section/:section_id/fields', (req, res, next) => {
    res.status(200).json(getSectionFields);
});

// /api/cms/section/{section_id}
router.post('/section/:section_id/fields', (req, res, next) => {
    res.status(200).json(postSectionField);
});

// /api/cms/section/{section_id}/fields/{field_id}
router.get('/section/:section_id/fields/:field_id', (req, res, next) => {
    res.status(200).json(getSectionField);
});

// /api/cms/section/{section_id}/fields/{field_id}
router.put('/section/:section_id/fields/:field_id', (req, res, next) => {
    res.status(200).json(putSectionField);
});

// /api/cms/article
router.get('/article', (req, res, next) => {
    res.status(200).json(getArticles);
});

// /api/cms/article
router.post('/article', (req, res, next) => {
    res.status(200).json(postArticle);
});

// /api/cms/article/countries
router.get('/article/countries', (req, res, next) => {
    res.status(200).json(articleCountries);
});

// /api/cms/article/{article_id}
router.get('/article/:article_id', (req, res, next) => {
    res.status(200).json(getArticle);
});

// /api/cms/article/{article_id}
router.put('/article/:article_id', (req, res, next) => {
    res.status(200).json(postArticle);
});

// /api/cms/media
router.get('/media', (req, res, next) => {
    res.status(200).json(cmsMedia);
});

// /api/cms/media/:id
router.get('/media/:id', (req, res, next) => {
    res.status(200).json(cmsMediaId);
});

export default router;