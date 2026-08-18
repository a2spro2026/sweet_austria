        (function seedDemoTestDataIfNeeded() {
            return;

            const produits = [
                { ref: 'PR0001', designation: 'Sac kraft 1 kg', categorie: 'Emballage', famille: 'Sacs', unite: 'UN', prix_achat: 1.2, prix_vente: 2 },
                { ref: 'PR0002', designation: 'Sac kraft 500 g', categorie: 'Emballage', famille: 'Sacs', unite: 'UN', prix_achat: 0.8, prix_vente: 1.4 },
                { ref: 'PR0003', designation: 'Carton 24 unités', categorie: 'Emballage', famille: 'Cartons', unite: 'CRT', prix_achat: 6.5, prix_vente: 9.8 },
                { ref: 'PR0004', designation: 'Étiquette ronde or', categorie: 'Conditionnement', famille: 'Étiquettes', unite: 'UN', prix_achat: 0.15, prix_vente: 0.35 },
                { ref: 'PR0005', designation: 'Ruban cadeau or 20 mm', categorie: 'Conditionnement', famille: 'Rubans', unite: 'UN', prix_achat: 12, prix_vente: 18.5 },
                { ref: 'PR0006', designation: 'Film étirable 45 cm', categorie: 'Emballage', famille: 'Films', unite: 'UN', prix_achat: 45, prix_vente: 68 },
                { ref: 'PR0007', designation: 'Pot verre 250 ml', categorie: 'Conditionnement', famille: 'Pots', unite: 'UN', prix_achat: 3.4, prix_vente: 5.2 },
                { ref: 'PR0008', designation: 'Couvercle pot 250 ml', categorie: 'Conditionnement', famille: 'Pots', unite: 'UN', prix_achat: 0.9, prix_vente: 1.5 },
                { ref: 'PR0009', designation: 'Barquette plastique 500 g', categorie: 'Emballage', famille: 'Barquettes', unite: 'UN', prix_achat: 0.7, prix_vente: 1.2 },
                { ref: 'PR0010', designation: 'Sac zip 250 g', categorie: 'Emballage', famille: 'Sacs', unite: 'UN', prix_achat: 0.55, prix_vente: 0.95 },
                { ref: 'PR0011', designation: 'Palette bois Europe', categorie: 'Logistique', famille: 'Palettes', unite: 'UN', prix_achat: 85, prix_vente: 120 },
                { ref: 'PR0012', designation: 'Scotch transparent 48 mm', categorie: 'Emballage', famille: 'Adhésifs', unite: 'UN', prix_achat: 8.5, prix_vente: 13 },
                { ref: 'PR0013', designation: 'Papier soie blanc', categorie: 'Conditionnement', famille: 'Papiers', unite: 'UN', prix_achat: 18, prix_vente: 28 },
                { ref: 'PR0014', designation: 'Sachet cellophane 20x30', categorie: 'Emballage', famille: 'Sachets', unite: 'UN', prix_achat: 0.25, prix_vente: 0.45 },
                { ref: 'PR0015', designation: 'Boîte métallique luxe', categorie: 'Conditionnement', famille: 'Boîtes', unite: 'UN', prix_achat: 14.5, prix_vente: 22 },
                { ref: 'PR0016', designation: 'Pince à sachet', categorie: 'Conditionnement', famille: 'Accessoires', unite: 'UN', prix_achat: 0.4, prix_vente: 0.8 },
                { ref: 'PR0017', designation: 'Gants nitrile boîte 100', categorie: 'Hygiène', famille: 'Protection', unite: 'UN', prix_achat: 28, prix_vente: 42 },
                { ref: 'PR0018', designation: 'Balance portable 5 kg', categorie: 'Matériel', famille: 'Pesage', unite: 'UN', prix_achat: 220, prix_vente: 340 },
                { ref: 'PR0019', designation: 'Marqueur alimentaire', categorie: 'Conditionnement', famille: 'Marquage', unite: 'UN', prix_achat: 6, prix_vente: 9.5 },
                { ref: 'PR0020', designation: 'Filet oignon 5 kg', categorie: 'Emballage', famille: 'Filets', unite: 'UN', prix_achat: 1.1, prix_vente: 1.9 }
            ];
            const fournisseurs = [
                { id: 'FR0003', nom: 'Atlas Fruits Secs', type: 'Vir' },
                { id: 'FR0004', nom: 'Maghreb Packaging', type: 'Chq' },
                { id: 'FR0005', nom: 'Sahara Import', type: 'Esp' },
                { id: 'FR0006', nom: 'Oriental Dry Nuts', type: 'Eff' },
                { id: 'FR0007', nom: 'Méditerranée Supplies', type: 'Vir' },
                { id: 'FR0008', nom: 'Al Amal Grossiste', type: 'Esp' },
                { id: 'FR0009', nom: 'Pack Plus Maroc', type: 'Chq' },
                { id: 'FR0010', nom: 'Agro Nador SARL', type: 'Vers' },
                { id: 'FR0001', nom: 'ste amine', type: 'Esp' },
                { id: 'FR0004', nom: 'Maghreb Packaging', type: 'Chq' }
            ];
            const clients = [
                { id: 'CL0001', nom: 'Superette Al Massira', type: 'Esp' },
                { id: 'CL0002', nom: 'Épicerie Al Qods', type: 'Esp' },
                { id: 'CL0003', nom: 'Hôtel Saidia Beach', type: 'Vir' },
                { id: 'CL0004', nom: 'Café Central', type: 'Chq' },
                { id: 'CL0005', nom: 'Marché Al Houda', type: 'Esp' },
                { id: 'CL0006', nom: 'Boutique Gourmet Tanger', type: 'Chq' },
                { id: 'CL0007', nom: 'Restaurant Al Andalous', type: 'Esp' },
                { id: 'CL0008', nom: 'Mini Market Atlas', type: 'Esp' },
                { id: 'CL0009', nom: 'Coopérative Femmes Nador', type: 'Vir' },
                { id: 'CL0010', nom: 'Distributeur Nord Est', type: 'Eff' }
            ];
            const qtys = [20, 40, 12, 80, 6, 10, 24, 50, 30, 15];
            const dates = ['2026-07-28', '2026-07-30', '2026-08-02', '2026-08-04', '2026-08-06', '2026-08-08', '2026-08-10', '2026-08-12', '2026-08-14', '2026-08-16'];

            function makeLines(start, count, priceKey) {
                const lines = [];
                for (let i = 0; i < count; i++) {
                    const p = produits[(start + i) % produits.length];
                    const qte = qtys[(start + i) % qtys.length];
                    const prix = Number(p[priceKey] || 0);
                    lines.push({
                        ref: p.ref,
                        designation: p.designation,
                        categorie: p.categorie,
                        famille: p.famille,
                        quantite: qte,
                        mesure: p.unite,
                        mesure_libelle: p.unite,
                        prix_u: prix,
                        prix: prix,
                        sous_total: Math.round(qte * prix * 100) / 100
                    });
                }
                return lines;
            }

            function parseStore(key) {
                try {
                    const parsed = JSON.parse(localStorage.getItem(key) || '[]');
                    return Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    return [];
                }
            }

            const achats = parseStore('commandesAchats');
            const ventes = parseStore('commandesVentes');
            const existingAchats = new Set(achats.map(c => c.bon));
            const existingVentes = new Set(ventes.map(c => c.bon));

            for (let i = 0; i < 10; i++) {
                const bon = 'ACH' + String(i + 1).padStart(4, '0');
                if (existingAchats.has(bon)) continue;
                const f = fournisseurs[i];
                const lignes = makeLines(i * 2, 2, 'prix_achat');
                const total = Math.round(lignes.reduce((s, l) => s + l.sous_total, 0) * 100) / 100;
                achats.push({
                    bon,
                    date_cmd: dates[i],
                    code_fournisseur: f.id,
                    nom_fournisseur: f.nom,
                    destination: 'Depot produit divers',
                    type_reglement: f.type,
                    echeance: dates[i],
                    recuperation: '',
                    ville_livraison: 'Nador',
                    transport: '',
                    matricule: '',
                    chauffeur: '',
                    photo: '',
                    lignes,
                    total,
                    paye: f.type === 'Esp',
                    saved_at: dates[i] + 'T10:00:00.000Z'
                });
            }

            for (let i = 0; i < 10; i++) {
                const bon = 'VTE' + String(i + 1).padStart(4, '0');
                if (existingVentes.has(bon)) continue;
                const c = clients[i];
                const lignes = makeLines(i * 2 + 1, 2, 'prix_vente');
                const total = Math.round(lignes.reduce((s, l) => s + l.sous_total, 0) * 100) / 100;
                ventes.push({
                    bon,
                    date_cmd: dates[i],
                    code_client: c.id,
                    nom_client: c.nom,
                    type_reglement: c.type,
                    echeance: dates[i],
                    lignes,
                    total,
                    paye: c.type === 'Esp',
                    saved_at: dates[i] + 'T11:00:00.000Z'
                });
            }

            localStorage.setItem('commandesAchats', JSON.stringify(achats));
            localStorage.setItem('commandesVentes', JSON.stringify(ventes));
            const maxAch = Math.max(Number(localStorage.getItem('achatsBonCounter') || 0), ...achats.map(c => {
                const m = String(c.bon || '').match(/(\d+)$/);
                return m ? Number(m[1]) : 0;
            }));
            const maxVte = Math.max(Number(localStorage.getItem('ventesBonCounter') || 0), ...ventes.map(c => {
                const m = String(c.bon || '').match(/(\d+)$/);
                return m ? Number(m[1]) : 0;
            }));
            localStorage.setItem('achatsBonCounter', String(maxAch));
            localStorage.setItem('ventesBonCounter', String(maxVte));
            localStorage.setItem('demoTestDataV1', '1');
        })();
