const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'https://vite-et-gourmand-rho.vercel.app';

const pagesToCapture = [
    { name: 'Accueil', url: '/index.html', needsAuth: false },
    { name: 'Menus', url: '/interface_frontend/pages/menus.html', needsAuth: false },
    { name: 'Admin', url: '/interface_frontend/admin/dashboard.html', needsAuth: true }
];

const wireframeCSS = `
    * {
        background-color: #f7f7f7 !important;
        background-image: none !important;
        color: #333 !important;
        border-color: #999 !important;
        box-shadow: none !important;
        text-shadow: none !important;
        border-radius: 0 !important;
    }
    img, video, iframe {
        opacity: 0.1 !important;
        border: 2px dashed #999 !important;
    }
    a, button {
        border: 1px solid #666 !important;
    }
`;

async function generate() {
    console.log("Lancement de Puppeteer...");
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();

    // Enable Request Interception
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
                    recent_orders: [
                        { id: 101, prenom: 'Jean', nom: 'Dupont', date_livraison: '2026-03-05', heure_livraison: '12:00:00', prix_total: '125.00', statut: 'en_attente' },
                        { id: 100, prenom: 'Marie', nom: 'Curie', date_livraison: '2026-03-04', heure_livraison: '19:30:00', prix_total: '65.50', statut: 'en_preparation' }
                    ]
                })
            });
        } else {
            request.continue();
        }
    });

    const tempDir = path.join(__dirname, 'temp_mockups');
    if (!fs.existsSync(tempDir)) fs.mkdirSync(tempDir);

    let htmlContent = `
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Charte Graphique - Vite & Gourmand</title>
        <style>
            body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; margin: 40px; }
            h1 { color: #1a1a1a; border-bottom: 2px solid #d4af37; padding-bottom: 10px; }
            h2 { color: #d4af37; margin-top: 40px; }
            .color-box { display: inline-block; width: 100px; height: 100px; margin-right: 20px; border-radius: 8px; border: 1px solid #ddd; position: relative; }
            .color-box span { position: absolute; bottom: -25px; left: 0; width: 100%; text-align: center; font-size: 12px; font-weight: bold; }
            .mockup-container { margin-top: 30px; page-break-inside: avoid; }
            .mockup-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; background: #eee; padding: 10px; }
            img { max-width: 100%; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
            .flex-row { display: flex; gap: 20px; align-items: flex-start; }
            .flex-col { flex: 1; }
            .mobile-img { max-width: 300px; margin: 0 auto; display: block; }
        </style>
    </head>
    <body>
        <h1>Vite & Gourmand - Charte Graphique Officielle</h1>
        <p>Généré automatiquement à partir de l'état actuel de l'application.</p>

        <h2>Couleurs Principales</h2>
        <div>
            <div class="color-box" style="background-color: #d4af37;"><span>#D4AF37<br>(Primaire)</span></div>
            <div class="color-box" style="background-color: #1a1a1a;"><span>#1A1A1A<br>(Secondaire)</span></div>
            <div class="color-box" style="background-color: #f7f7f7;"><span>#F7F7F7<br>(Fond)</span></div>
            <div class="color-box" style="background-color: #2c3e50;"><span>#2C3E50<br>(Texte)</span></div>
        </div>

        <h2>Typographies</h2>
        <div style="font-family: 'Playfair Display', serif; font-size: 24px; margin-bottom: 10px;">Playfair Display (Titres) - Aa Bb Cc Dd Ee Ff 0123456789</div>
        <div style="font-family: 'Lato', sans-serif; font-size: 18px;">Lato (Corps de texte) - Aa Bb Cc Dd Ee Ff 0123456789</div>
        
        <div style="page-break-after: always;"></div>
        <h1>Maquettes Interactives & Wireframes</h1>
    `;

    for (const p of pagesToCapture) {
        console.log(`Capture de ${p.name}...`);
        const fullUrl = BASE_URL + p.url;
        const pageName = p.name.toLowerCase();

        // DESKTOP
        await page.setViewport({ width: 1440, height: 900 });
        await page.goto(fullUrl, { waitUntil: 'networkidle0', timeout: 30000 });

        // Anti-animations
        await page.evaluate(() => {
            const style = document.createElement('style');
            style.innerHTML = '* { transition: none !important; animation: none !important; }';
            document.head.appendChild(style);
        });

        const desktopImg = `${pageName}_desktop.png`;
        await page.screenshot({ path: path.join(tempDir, desktopImg), fullPage: false }); // Avoid extremely long pages, just capture viewport or realistic fold

        // Wireframe Desktop
        await page.addStyleTag({ content: wireframeCSS });
        const desktopWireImg = `${pageName}_desktop_wire.png`;
        await page.screenshot({ path: path.join(tempDir, desktopWireImg), fullPage: false });

        // MOBILE
        await page.setViewport({ width: 375, height: 812, isMobile: true });
        await page.reload({ waitUntil: 'networkidle0' });
        await page.evaluate(() => {
            const style = document.createElement('style');
            style.innerHTML = '* { transition: none !important; animation: none !important; }';
            document.head.appendChild(style);
        });
        const mobileImg = `${pageName}_mobile.png`;
        await page.screenshot({ path: path.join(tempDir, mobileImg), fullPage: false });

        // Wireframe Mobile
        await page.addStyleTag({ content: wireframeCSS });
        const mobileWireImg = `${pageName}_mobile_wire.png`;
        await page.screenshot({ path: path.join(tempDir, mobileWireImg), fullPage: false });

        // Add to HTML
        htmlContent += `
        <div class="mockup-container">
            <div class="mockup-title">Vue : ${p.name} - Desktop (1440px)</div>
            <div class="flex-row">
                <div class="flex-col">
                    <p><strong>Haute Fidélité</strong></p>
                    <img src="file:///${path.join(tempDir, desktopImg).replace(/\\/g, '/')}" />
                </div>
                <div class="flex-col">
                    <p><strong>Wireframe</strong></p>
                    <img src="file:///${path.join(tempDir, desktopWireImg).replace(/\\/g, '/')}" />
                </div>
            </div>
        </div>
        
        <div class="mockup-container">
            <div class="mockup-title">Vue : ${p.name} - Mobile (375px)</div>
            <div class="flex-row">
                <div class="flex-col">
                    <p><strong>Haute Fidélité</strong></p>
                    <img src="file:///${path.join(tempDir, mobileImg).replace(/\\/g, '/')}" class="mobile-img" />
                </div>
                <div class="flex-col">
                    <p><strong>Wireframe</strong></p>
                    <img src="file:///${path.join(tempDir, mobileWireImg).replace(/\\/g, '/')}" class="mobile-img" />
                </div>
            </div>
        </div>
        <div style="page-break-after: always;"></div>
        `;
    }

    htmlContent += `</body></html>`;
    const htmlPath = path.join(__dirname, 'charte_temp.html');
    fs.writeFileSync(htmlPath, htmlContent);

    console.log("Génération du PDF final...");
    await page.setViewport({ width: 1200, height: 800 });
    await page.goto(`file:///${htmlPath.replace(/\\/g, '/')}`, { waitUntil: 'networkidle0' });

    await page.pdf({
        path: path.join(__dirname, 'Charte graphique.pdf'),
        format: 'A4',
        printBackground: true,
        margin: { top: '20px', bottom: '20px', left: '20px', right: '20px' }
    });

    await browser.close();

    // Cleanup temp files
    fs.readdirSync(tempDir).forEach(f => fs.unlinkSync(path.join(tempDir, f)));
    fs.rmdirSync(tempDir);
    fs.unlinkSync(htmlPath);

    console.log("PDF généré avec succès dans : Charte graphique.pdf");
}

generate().catch(err => console.error(err));
