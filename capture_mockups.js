const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'https://vite-et-gourmand-rho.vercel.app';
const destDir = 'C:\\Users\\kaoso\\.gemini\\antigravity\\brain\\dd91c9c3-b90a-4b66-9085-61017ca193c9';

const pagesToCapture = [
    { name: 'Accueil', url: '/index.html' },
    { name: 'Menus', url: '/interface_frontend/pages/menus.html' },
    { name: 'Admin', url: '/interface_frontend/admin/dashboard.html' }
];

async function captureMockups() {
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();

    await page.setRequestInterception(true);
    page.on('request', request => {
        const url = request.url();
        if (url.includes('/auth/me.php')) {
            request.respond({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    logged_in: true,
                    user: { id: 1, prenom: 'Julie', nom: 'Chef', role: 'admin', email: 'admin@vite-gourmand.fr' }
                })
            });
        } else if (url.includes('/stats.php')) {
            request.respond({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    active_orders: 12,
                    revenue_today: 450.50,
                    pending_reviews: 3,
                    recent_orders: []
                })
            });
        } else {
            request.continue();
        }
    });

    for (const p of pagesToCapture) {
        const fullUrl = BASE_URL + p.url;
        const pageName = p.name.toLowerCase();

        // DESKTOP
        await page.setViewport({ width: 1440, height: 900 });
        await page.goto(fullUrl, { waitUntil: 'networkidle0', timeout: 30000 });
        await page.evaluate(() => {
            const style = document.createElement('style');
            style.innerHTML = '* { transition: none !important; animation: none !important; }';
            document.head.appendChild(style);
        });
        await page.screenshot({ path: path.join(destDir, `mockup_${pageName}_desktop.png`), fullPage: false });

        // MOBILE
        await page.setViewport({ width: 375, height: 812, isMobile: true });
        await page.reload({ waitUntil: 'networkidle0' });
        await page.evaluate(() => {
            const style = document.createElement('style');
            style.innerHTML = '* { transition: none !important; animation: none !important; }';
            document.head.appendChild(style);
        });
        await page.screenshot({ path: path.join(destDir, `mockup_${pageName}_mobile.png`), fullPage: false });
    }

    await browser.close();
    console.log("Mockups successfully captured to brain directory.");
}

captureMockups().catch(err => console.error(err));
