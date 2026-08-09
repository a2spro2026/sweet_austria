{{-- Section Client — JS (doit être inclus dans le même <script> que dashboard) --}}

        /* ── Fiche Client ── */
        let clients = [];
        let nextClientId = 'CL0001';
        let editingClientId = null;
        let viewingClient = false;

        const ficheClientView = document.getElementById('ficheClientView');
        const clientFormPanel = document.getElementById('clientFormPanel');
        const clientListPanel = document.getElementById('clientListPanel');
        const ficheClientForm = document.getElementById('ficheClientForm');
        const clientsTableBody = document.getElementById('clientsTableBody');
        const clIdInput = document.getElementById('cl_id');

        async function loadClientsReturn() {
            try {
                const res = await fetch('/api/clients');
                if (!res.ok) throw new Error('Erreur chargement clients');
                return await res.json();
            } catch (err) {
                console.error(err);
                return null;
            }
        }

        async function loadClients() {
            const data = await loadClientsReturn();
            if (data) {
                clients = data.clients || [];
                nextClientId = data.next_id || 'CL0001';
                if (clIdInput) clIdInput.value = '';
            }
        }

        function setClientFormReadonly(readonly) {
            viewingClient = !!readonly;
            ['cl_nom','cl_type','cl_statut','cl_adresse','cl_telephone','cl_fixe','cl_ville','cl_email','cl_type_paiement','cl_solde','cl_banque','cl_rib'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = !!readonly;
            });
            const valider = document.getElementById('validerClientBtn');
            if (valider) valider.classList.toggle('hidden', !!readonly);
        }

        function resetClientFormMode() {
            editingClientId = null;
            viewingClient = false;
            setClientFormReadonly(false);
            const title = document.getElementById('clientFormTitle');
            const subtitle = document.getElementById('clientFormSubtitle');
            if (title) title.textContent = 'Fiche Client';
            if (subtitle) subtitle.textContent = 'Barre de saisie';
        }

        function populateClientForm(c) {
            if (!c) return;
            if (clIdInput) clIdInput.value = c.id || '';
            const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
            set('cl_nom', c.nom);
            set('cl_type', c.type);
            set('cl_statut', c.statut);
            set('cl_adresse', c.adresse);
            set('cl_telephone', c.telephone);
            set('cl_fixe', c.fixe);
            set('cl_ville', c.ville);
            set('cl_email', c.email);
            set('cl_type_paiement', c.type_paiement);
            set('cl_solde', c.solde != null ? c.solde : '');
            set('cl_banque', c.banque);
            set('cl_rib', c.rib);
        }

        function showClientForm(reset = false) {
            document.body.classList.remove('table-list-active');
            if (clientFormPanel) clientFormPanel.classList.remove('hidden');
            if (clientListPanel) clientListPanel.classList.add('hidden');
            if (reset) {
                resetClientFormMode();
                if (ficheClientForm) ficheClientForm.reset();
                if (clIdInput) clIdInput.value = nextClientId || '';
                fillClientVilleDatalist();
            }
        }

        function showClientList() {
            document.body.classList.add('table-list-active');
            if (clientFormPanel) clientFormPanel.classList.add('hidden');
            if (clientListPanel) clientListPanel.classList.remove('hidden');
            renderClientsTable();
        }

        function fillClientVilleDatalist() {
            const dl = document.getElementById('cl_ville_list');
            if (!dl || typeof LOOKUP_LISTS === 'undefined') return;
            dl.innerHTML = (LOOKUP_LISTS.villes || []).map(v => `<option value="${escapeOptionAttr(v)}"></option>`).join('');
        }

        function renderClientsTable() {
            if (!clientsTableBody) return;
            if (!clients.length) {
                clientsTableBody.innerHTML = '<tr><td colspan="8" class="fournisseur-empty">Aucun client enregistré</td></tr>';
                return;
            }
            clientsTableBody.innerHTML = clients.map(c => {
                const solde = parseFloat(c.solde) || 0;
                return `<tr data-id="${escHtml(c.id)}">
                    <td><strong>${escHtml(c.id)}</strong></td>
                    <td>${escHtml(c.nom)}</td>
                    <td>${escHtml(c.type)}</td>
                    <td>${escHtml(c.ville)}</td>
                    <td>${escHtml(c.telephone)}</td>
                    <td>${escHtml(c.statut)}</td>
                    <td class="solde-cell ${soldeClass(solde)}">${formatSolde(solde)}</td>
                    <td class="col-actions" onclick="event.stopPropagation()">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-view" data-cl-view="${escHtml(c.id)}" title="Voir"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg></button>
                            <button type="button" class="btn-icon-row btn-icon-edit" data-cl-edit="${escHtml(c.id)}" title="Modifier"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                            <button type="button" class="btn-icon-row btn-icon-delete" data-cl-delete="${escHtml(c.id)}" title="Supprimer"><svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="3 6 5 6 21 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                        </span>
                    </td>
                </tr>`;
            }).join('');

            clientsTableBody.querySelectorAll('[data-cl-view]').forEach(btn => {
                btn.addEventListener('click', () => voirClient(btn.dataset.clView));
            });
            clientsTableBody.querySelectorAll('[data-cl-edit]').forEach(btn => {
                btn.addEventListener('click', () => editClient(btn.dataset.clEdit));
            });
            clientsTableBody.querySelectorAll('[data-cl-delete]').forEach(btn => {
                btn.addEventListener('click', () => deleteClient(btn.dataset.clDelete));
            });
        }

        function voirClient(code) {
            const c = clients.find(x => x.id === code);
            if (!c) return;
            editingClientId = null;
            showClientForm(false);
            populateClientForm(c);
            setClientFormReadonly(true);
            const title = document.getElementById('clientFormTitle');
            const subtitle = document.getElementById('clientFormSubtitle');
            if (title) title.textContent = 'Consulter Client';
            if (subtitle) subtitle.textContent = c.id;
        }

        function editClient(code) {
            const c = clients.find(x => x.id === code);
            if (!c) return;
            editingClientId = code;
            viewingClient = false;
            showClientForm(false);
            populateClientForm(c);
            setClientFormReadonly(false);
            const title = document.getElementById('clientFormTitle');
            const subtitle = document.getElementById('clientFormSubtitle');
            if (title) title.textContent = 'Modifier Client';
            if (subtitle) subtitle.textContent = c.id;
            fillClientVilleDatalist();
        }

        async function deleteClient(code) {
            const c = clients.find(x => x.id === code);
            if (!c) return;
            if (!confirm('Supprimer le client « ' + c.nom + ' » ?')) return;
            try {
                const res = await fetch('/api/clients/' + encodeURIComponent(code), {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                });
                if (!res.ok) throw new Error('Erreur suppression');
                await loadClients();
                renderClientsTable();
            } catch (err) {
                console.error(err);
                alert('Impossible de supprimer le client.');
            }
        }

        async function saveClient() {
            if (viewingClient) return;
            const nom = document.getElementById('cl_nom')?.value?.trim() || '';
            if (!nom) {
                alert('Veuillez saisir le nom du client.');
                document.getElementById('cl_nom')?.focus();
                return;
            }
            const isEdit = !!editingClientId;
            const payload = {
                nom,
                type: document.getElementById('cl_type')?.value || '',
                ville: document.getElementById('cl_ville')?.value?.trim() || '',
                adresse: document.getElementById('cl_adresse')?.value?.trim() || '',
                telephone: document.getElementById('cl_telephone')?.value?.trim() || '',
                fixe: document.getElementById('cl_fixe')?.value?.trim() || '',
                email: document.getElementById('cl_email')?.value?.trim() || '',
                statut: document.getElementById('cl_statut')?.value || '',
                type_paiement: document.getElementById('cl_type_paiement')?.value || '',
                banque: document.getElementById('cl_banque')?.value?.trim() || '',
                rib: document.getElementById('cl_rib')?.value?.trim() || '',
                solde: parseFloat(document.getElementById('cl_solde')?.value) || 0,
            };
            const validerBtn = document.getElementById('validerClientBtn');
            if (validerBtn) validerBtn.disabled = true;
            try {
                const url = isEdit
                    ? '/api/clients/' + encodeURIComponent(editingClientId)
                    : '/api/clients';
                const res = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(err.message || 'Erreur enregistrement');
                }
                await loadClients();
                showClientList();
            } catch (err) {
                console.error(err);
                alert(err.message || 'Impossible d\'enregistrer le client.');
            } finally {
                if (validerBtn) validerBtn.disabled = false;
            }
        }

        ficheClientForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            saveClient();
        });
        document.getElementById('ajouterClientBtn')?.addEventListener('click', () => showClientForm(true));
        document.getElementById('fermerClientForm')?.addEventListener('click', () => showClientList());
        document.getElementById('fermerClientListBtn')?.addEventListener('click', () => {
            document.querySelector('.nav-item[data-view="dashboard"]')?.click();
        });

        function findClientByNom(nom) {
            const q = String(nom || '').trim().toLowerCase();
            if (!q) return null;
            return (clients || []).find(c => String(c.nom || '').trim().toLowerCase() === q)
                || (clients || []).find(c => String(c.nom || '').trim().toLowerCase().includes(q))
                || null;
        }

        function findClientByCode(code) {
            const q = String(code || '').trim().toLowerCase();
            if (!q) return null;
            return (clients || []).find(c => String(c.id || '').trim().toLowerCase() === q) || null;
        }

        function normalizeClientNom(nom) {
            return String(nom || '').trim().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        function commandeBelongsToClient(c, client) {
            if (!c || !client) return false;
            const code = String(c.code_client || '').trim().toLowerCase();
            const nom = normalizeClientNom(c.nom_client);
            const id = String(client.id || '').trim().toLowerCase();
            const cNom = normalizeClientNom(client.nom);
            return (code && code === id) || (nom && cNom && nom === cNom);
        }

        /* ── Bon de Vente ── */
        let commandesVentes = [];
        let ventesLignes = [];
        let editingVenteIndex = null;
        let editingVenteLineIndex = null;
        let ventesBonCounter = parseInt(localStorage.getItem('ventesBonCounter') || '0', 10) || 0;
        let ventesReturnView = 'dashboard';

        const ventesView = document.getElementById('ventesView');
        const ventesForm = document.getElementById('ventesForm');

        function loadCommandesVentesStore() {
            try {
                const raw = localStorage.getItem('commandesVentes');
                const parsed = raw ? JSON.parse(raw) : [];
                commandesVentes = Array.isArray(parsed) ? parsed : [];
            } catch (err) {
                console.error('Lecture bons de vente:', err);
                commandesVentes = Array.isArray(commandesVentes) ? commandesVentes : [];
            }
            return commandesVentes;
        }

        function persistCommandesVentes() {
            try {
                localStorage.setItem('commandesVentes', JSON.stringify(commandesVentes || []));
            } catch (err) {
                console.error('Sauvegarde bons de vente:', err);
                alert('Impossible d\'enregistrer les bons de vente (mémoire navigateur pleine).');
            }
        }

        function nextVentesBonNumber() {
            ventesBonCounter += 1;
            localStorage.setItem('ventesBonCounter', String(ventesBonCounter));
            return 'VTE' + String(ventesBonCounter).padStart(4, '0');
        }

        function updateVentesClientDatalists() {
            const dl = document.getElementById('veClientNomsList');
            if (!dl) return;
            const names = (clients || []).map(c => c.nom).filter(Boolean);
            dl.innerHTML = names.map(n => `<option value="${escapeOptionAttr(n)}"></option>`).join('');
        }

        function updateVentesDesignationDatalist() {
            const dl = document.getElementById('ve_ligne_designation_list');
            if (!dl) return;
            const fromLookup = (typeof LOOKUP_LISTS !== 'undefined' && LOOKUP_LISTS.designations) ? LOOKUP_LISTS.designations : [];
            const fromProduits = (typeof produits !== 'undefined' && Array.isArray(produits))
                ? produits.map(p => p.designation || p.libelle || p.nom).filter(Boolean)
                : [];
            const list = uniqueSortedList([...fromLookup, ...fromProduits]);
            dl.innerHTML = list.map(n => `<option value="${escapeOptionAttr(n)}"></option>`).join('');

            const mesureDl = document.getElementById('ve_ligne_mesure_list');
            if (mesureDl && typeof unitesMesure !== 'undefined' && Array.isArray(unitesMesure) && unitesMesure.length) {
                mesureDl.innerHTML = unitesMesure.map(u =>
                    `<option value="${escapeOptionAttr(u.code)}">${escapeOptionText((u.libelle || u.code) + ' (' + u.code + ')')}</option>`
                ).join('');
            }
        }

        function fillVentesClientFields(c) {
            if (!c) return;
            const codeEl = document.getElementById('ve_code_client');
            const nomEl = document.getElementById('ve_nom_client');
            if (codeEl) codeEl.value = c.id || '';
            if (nomEl) nomEl.value = c.nom || '';
            const typeEl = document.getElementById('ve_type_reglement');
            if (typeEl && !typeEl.value && c.type_paiement) typeEl.value = c.type_paiement;
        }

        function lookupVentesClientByNom() {
            const nom = document.getElementById('ve_nom_client')?.value || '';
            const c = findClientByNom(nom);
            if (c) fillVentesClientFields(c);
        }

        function calcVentesLigneSousTotal() {
            const qte = parseFloat(document.getElementById('ve_ligne_quantite')?.value) || 0;
            const prix = parseFloat(document.getElementById('ve_ligne_prix')?.value) || 0;
            const st = Math.round(qte * prix * 100) / 100;
            const el = document.getElementById('ve_ligne_sous_total');
            if (el) el.value = st ? st.toFixed(2) : '';
            return st;
        }

        function clearVentesLigneForm() {
            ['ve_ligne_ref','ve_ligne_designation','ve_ligne_quantite','ve_ligne_mesure','ve_ligne_prix','ve_ligne_sous_total'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            editingVenteLineIndex = null;
        }

        function updateVentesTotalGeneral() {
            const total = ventesLignes.reduce((s, l) => s + (parseFloat(l.sous_total) || 0), 0);
            const el = document.getElementById('ventesTotalGeneral');
            if (el) el.textContent = (typeof formatMoney === 'function' ? formatMoney(total) : (total.toFixed(2) + ' MAD'));
        }

        function renderVentesLignesTable() {
            const tbody = document.getElementById('ventesLignesTableBody');
            if (!tbody) return;
            if (!ventesLignes.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="fournisseur-empty">Aucun article</td></tr>';
                updateVentesTotalGeneral();
                return;
            }
            const fmt = (typeof formatMoney === 'function') ? formatMoney : (v) => (parseFloat(v) || 0).toFixed(2);
            tbody.innerHTML = ventesLignes.map((l, i) => `
                <tr>
                    <td>${escHtml(l.ref)}</td>
                    <td>${escHtml(l.designation)}</td>
                    <td>${escHtml(String(l.quantite))}</td>
                    <td>${escHtml(l.mesure)}</td>
                    <td>${escHtml(fmt(l.prix))}</td>
                    <td><strong>${escHtml(fmt(l.sous_total))}</strong></td>
                    <td class="col-actions">
                        <button type="button" class="btn-icon-row btn-icon-delete" data-ve-line-del="${i}" title="Supprimer">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="3 6 5 6 21 6" stroke-width="2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-width="2"/></svg>
                        </button>
                    </td>
                </tr>
            `).join('');
            tbody.querySelectorAll('[data-ve-line-del]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.veLineDel, 10);
                    if (!Number.isNaN(idx)) {
                        ventesLignes.splice(idx, 1);
                        renderVentesLignesTable();
                    }
                });
            });
            updateVentesTotalGeneral();
        }

        function addVentesLigne() {
            calcVentesLigneSousTotal();
            const ref = document.getElementById('ve_ligne_ref')?.value?.trim() || '';
            const designation = document.getElementById('ve_ligne_designation')?.value?.trim() || '';
            const quantite = parseFloat(document.getElementById('ve_ligne_quantite')?.value) || 0;
            const mesure = document.getElementById('ve_ligne_mesure')?.value?.trim() || '';
            const prix = parseFloat(document.getElementById('ve_ligne_prix')?.value) || 0;
            const sous_total = Math.round(quantite * prix * 100) / 100;
            if (!designation) {
                alert('Saisissez la désignation.');
                document.getElementById('ve_ligne_designation')?.focus();
                return;
            }
            if (quantite <= 0) {
                alert('Saisissez une quantité valide.');
                document.getElementById('ve_ligne_quantite')?.focus();
                return;
            }
            const data = { ref, designation, quantite, mesure, prix, sous_total };
            if (editingVenteLineIndex != null) ventesLignes[editingVenteLineIndex] = data;
            else ventesLignes.push(data);
            clearVentesLigneForm();
            renderVentesLignesTable();
        }

        function getVentesHeaderInfo() {
            return {
                bon: document.getElementById('ve_bon')?.value?.trim() || nextVentesBonNumber(),
                date_cmd: document.getElementById('ve_date_cmd')?.value || todayIsoDate(),
                code_client: document.getElementById('ve_code_client')?.value || '',
                nom_client: document.getElementById('ve_nom_client')?.value?.trim() || '',
                type_reglement: document.getElementById('ve_type_reglement')?.value || '',
                echeance: document.getElementById('ve_echeance')?.value || '',
            };
        }

        function resetVentesForm() {
            if (!ventesForm) return;
            ventesForm.reset();
            const dateCmd = document.getElementById('ve_date_cmd');
            if (dateCmd) dateCmd.value = todayIsoDate();
            const bon = document.getElementById('ve_bon');
            if (bon) bon.value = '';
            document.getElementById('ve_code_client').value = '';
            ventesLignes = [];
            editingVenteIndex = null;
            editingVenteLineIndex = null;
            clearVentesLigneForm();
            renderVentesLignesTable();
        }

        function showVentesConsult() {
            const consult = document.getElementById('ventesConsultMode');
            const saisie = document.getElementById('ventesSaisieMode');
            if (consult) consult.classList.remove('hidden');
            if (saisie) saisie.classList.add('hidden');
            loadCommandesVentesStore();
            renderCommandesVentesTable();
        }

        function showVentesSaisie(reset = true) {
            const consult = document.getElementById('ventesConsultMode');
            const saisie = document.getElementById('ventesSaisieMode');
            if (consult) consult.classList.add('hidden');
            if (saisie) saisie.classList.remove('hidden');
            if (reset) resetVentesForm();
            updateVentesClientDatalists();
            updateVentesDesignationDatalist();
        }

        function renderCommandesVentesTable() {
            const tbody = document.getElementById('commandesListTableBodyVentes');
            if (!tbody) return;
            loadCommandesVentesStore();
            if (!commandesVentes.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="achats-commandes-empty">Aucun bon de vente</td></tr>';
                return;
            }
            const fmt = (typeof formatMoney === 'function') ? formatMoney : (v) => (parseFloat(v) || 0).toFixed(2) + ' MAD';
            tbody.innerHTML = commandesVentes.map((c, i) => {
                const qte = (c.lignes || []).reduce((s, l) => s + (parseFloat(l.quantite) || 0), 0);
                const paye = c.paye === true || (typeof isCommandePayee === 'function' && isCommandePayee(c));
                return `<tr data-vente-index="${i}">
                    <td><strong>${escHtml(c.bon)}</strong></td>
                    <td>${formatDateFr(c.date_cmd)}</td>
                    <td>${escHtml(c.code_client) || '—'}</td>
                    <td class="cmd-col-nom" title="${escHtml(c.nom_client) || ''}">${escHtml(c.nom_client) || '—'}</td>
                    <td>${qte.toLocaleString('fr-FR')}</td>
                    <td><strong>${escHtml(fmt(c.total || 0))}</strong></td>
                    <td>${escHtml(c.type_reglement) || '—'}${paye ? ' ✓' : ''}</td>
                    <td class="col-actions col-actions-cmd no-print-cmd" onclick="event.stopPropagation()">
                        <span class="cmd-actions-wrap">
                            <button type="button" class="btn-row btn-row-edit" data-ve-regler="${i}" ${paye ? 'style="display:none"' : ''}>Régler</button>
                            <button type="button" class="btn-row btn-row-edit" data-ve-voir="${i}">Voir</button>
                            <button type="button" class="btn-row btn-row-edit" data-ve-mod="${i}">Modifier</button>
                            <button type="button" class="btn-row btn-row-delete" data-ve-suppr="${i}">Supprimer</button>
                        </span>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('[data-ve-voir]').forEach(btn => {
                btn.addEventListener('click', () => voirBonVente(parseInt(btn.dataset.veVoir, 10)));
            });
            tbody.querySelectorAll('[data-ve-mod]').forEach(btn => {
                btn.addEventListener('click', () => modifierBonVente(parseInt(btn.dataset.veMod, 10)));
            });
            tbody.querySelectorAll('[data-ve-suppr]').forEach(btn => {
                btn.addEventListener('click', () => supprimerBonVente(parseInt(btn.dataset.veSuppr, 10)));
            });
            tbody.querySelectorAll('[data-ve-regler]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.veRegler, 10);
                    const c = commandesVentes[idx];
                    if (!c) return;
                    showAppView('reglement-ventes');
                    setTimeout(() => {
                        showReglementVenteForm(null, false);
                        const cl = document.getElementById('rv_client');
                        if (cl) {
                            cl.value = c.nom_client || '';
                            cl.dispatchEvent(new Event('change'));
                        }
                    }, 50);
                });
            });
        }

        function fillVentesFormFromCommande(c) {
            if (!c) return;
            const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
            set('ve_bon', c.bon);
            set('ve_date_cmd', c.date_cmd);
            set('ve_code_client', c.code_client);
            set('ve_nom_client', c.nom_client);
            set('ve_type_reglement', c.type_reglement);
            set('ve_echeance', c.echeance);
            ventesLignes = (c.lignes || []).map(l => ({ ...l }));
            renderVentesLignesTable();
        }

        function voirBonVente(index) {
            const c = commandesVentes[index];
            if (!c) return;
            editingVenteIndex = null;
            showVentesSaisie(false);
            fillVentesFormFromCommande(c);
            ['ve_bon','ve_nom_client','ve_type_reglement','ve_echeance','ve_ligne_ref','ve_ligne_designation','ve_ligne_quantite','ve_ligne_mesure','ve_ligne_prix'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = true;
            });
            const addBtn = document.getElementById('ajouterLigneVentesBtn');
            const saveBtn = document.getElementById('enregistrerCommandeVentesBtn');
            if (addBtn) addBtn.disabled = true;
            if (saveBtn) saveBtn.classList.add('hidden');
        }

        function modifierBonVente(index) {
            const c = commandesVentes[index];
            if (!c) return;
            editingVenteIndex = index;
            showVentesSaisie(false);
            fillVentesFormFromCommande(c);
            ['ve_bon','ve_nom_client','ve_type_reglement','ve_echeance','ve_ligne_ref','ve_ligne_designation','ve_ligne_quantite','ve_ligne_mesure','ve_ligne_prix'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = false;
            });
            const addBtn = document.getElementById('ajouterLigneVentesBtn');
            const saveBtn = document.getElementById('enregistrerCommandeVentesBtn');
            if (addBtn) addBtn.disabled = false;
            if (saveBtn) saveBtn.classList.remove('hidden');
        }

        function supprimerBonVente(index) {
            const c = commandesVentes[index];
            if (!c) return;
            if (!confirm('Supprimer le bon de vente « ' + c.bon + ' » ?')) return;
            commandesVentes.splice(index, 1);
            persistCommandesVentes();
            renderCommandesVentesTable();
            if (typeof refreshDashboardAnalytics === 'function') refreshDashboardAnalytics();
        }

        function enregistrerBonVente() {
            lookupVentesClientByNom();
            const header = getVentesHeaderInfo();
            if (!header.nom_client && !header.code_client) {
                alert('Veuillez saisir le client.');
                document.getElementById('ve_nom_client')?.focus();
                return;
            }
            if (!ventesLignes.length) {
                alert('Ajoutez au moins une ligne article.');
                document.getElementById('ve_ligne_designation')?.focus();
                return;
            }
            const total = Math.round(ventesLignes.reduce((s, l) => s + (parseFloat(l.sous_total) || 0), 0) * 100) / 100;
            const previous = editingVenteIndex !== null ? { ...commandesVentes[editingVenteIndex] } : null;
            const commande = {
                ...header,
                lignes: ventesLignes.map(l => ({ ...l })),
                total,
                paye: editingVenteIndex !== null
                    ? previous?.paye === true
                    : header.type_reglement === 'Esp',
                saved_at: new Date().toISOString(),
            };
            if (editingVenteIndex !== null) commandesVentes[editingVenteIndex] = commande;
            else commandesVentes.unshift(commande);
            persistCommandesVentes();
            editingVenteIndex = null;
            resetVentesForm();
            showVentesConsult();
            if (typeof refreshDashboardAnalytics === 'function') refreshDashboardAnalytics();
            if (balanceClientsView && !balanceClientsView.classList.contains('hidden')) renderBalanceClientsTable();
        }

        document.getElementById('nouveauBonVentesBtn')?.addEventListener('click', () => {
            ['ve_bon','ve_nom_client','ve_type_reglement','ve_echeance','ve_ligne_ref','ve_ligne_designation','ve_ligne_quantite','ve_ligne_mesure','ve_ligne_prix'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = false;
            });
            document.getElementById('ajouterLigneVentesBtn') && (document.getElementById('ajouterLigneVentesBtn').disabled = false);
            document.getElementById('enregistrerCommandeVentesBtn')?.classList.remove('hidden');
            showVentesSaisie(true);
        });
        document.getElementById('fermerVentesConsultBtn')?.addEventListener('click', () => {
            document.querySelector('.nav-item[data-view="dashboard"]')?.click();
        });
        document.getElementById('fermerVentesBtn')?.addEventListener('click', () => showVentesConsult());
        document.getElementById('enregistrerCommandeVentesBtn')?.addEventListener('click', enregistrerBonVente);
        document.getElementById('ajouterLigneVentesBtn')?.addEventListener('click', addVentesLigne);
        document.getElementById('ve_nom_client')?.addEventListener('change', lookupVentesClientByNom);
        document.getElementById('ve_nom_client')?.addEventListener('blur', lookupVentesClientByNom);
        ['ve_ligne_quantite','ve_ligne_prix'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', calcVentesLigneSousTotal);
        });

        /* ── Réglement Vente ── */
        const REGLEMENTS_VENTES_KEY = 'reglementsVentes';
        let reglementsVentes = [];
        let editingReglementVenteId = null;
        let reglementVenteReadonly = false;

        const reglementVentesView = document.getElementById('reglementVentesView');
        const reglementVenteFormPanel = document.getElementById('reglementVenteFormPanel');
        const reglementVenteConsultPanel = document.getElementById('reglementVenteConsultPanel');
        const reglementsVenteTableBody = document.getElementById('reglementsVenteTableBody');

        function loadReglementsVentes() {
            try {
                reglementsVentes = JSON.parse(localStorage.getItem(REGLEMENTS_VENTES_KEY) || '[]');
                if (!Array.isArray(reglementsVentes)) reglementsVentes = [];
            } catch (e) {
                reglementsVentes = [];
            }
            return reglementsVentes;
        }

        function saveReglementsVentes() {
            localStorage.setItem(REGLEMENTS_VENTES_KEY, JSON.stringify(reglementsVentes));
        }

        function nextReglementVenteRef() {
            const nums = reglementsVentes
                .map(r => {
                    const m = String(r.ref || '').match(/(\d+)\s*$/);
                    return m ? parseInt(m[1], 10) : NaN;
                })
                .filter(n => !Number.isNaN(n));
            const next = (nums.length ? Math.max(...nums) : 0) + 1;
            return 'RégV' + String(next).padStart(4, '0');
        }

        function getMontantPayeBonVente(bon, excludeId = null) {
            const key = String(bon || '').trim();
            if (!key) return 0;
            return (reglementsVentes || [])
                .filter(r => String(r.bon || '').trim() === key)
                .filter(r => !excludeId || r.id !== excludeId)
                .reduce((s, r) => s + (parseFloat(r.montant_reg) || 0), 0);
        }

        function getSoldeRestantBonVente(c, excludeId = null) {
            if (!c) return 0;
            const total = parseFloat(c.total || 0) || 0;
            return Math.round((total - getMontantPayeBonVente(c.bon, excludeId)) * 100) / 100;
        }

        function isBonVenteNonSolde(c, includeBon = '') {
            if (!c) return false;
            if (includeBon && String(c.bon || '') === String(includeBon)) return true;
            if (c.paye === true) return false;
            if (typeof isCommandePayee === 'function' && isCommandePayee(c)) return false;
            return getSoldeRestantBonVente(c) > 0.009;
        }

        function getBonsVenteNonSoldesForClient(nomClient, includeBon = '') {
            const q = String(nomClient || '').trim();
            if (!q) return [];
            loadCommandesVentesStore();
            loadReglementsVentes();
            const client = findClientByNom(q);
            return (commandesVentes || []).filter(c => {
                if (!isBonVenteNonSolde(c, includeBon)) return false;
                if (client && commandeBelongsToClient(c, client)) return true;
                const nom = String(c.nom_client || '').trim().toLowerCase();
                const qq = q.toLowerCase();
                return nom === qq || nom.includes(qq);
            });
        }

        function fillReglementVenteClientDatalist() {
            const dl = document.getElementById('rv_client_list');
            if (!dl) return;
            const names = new Set();
            (clients || []).forEach(c => { if (c?.nom) names.add(String(c.nom).trim()); });
            (commandesVentes || []).forEach(c => { if (c?.nom_client) names.add(String(c.nom_client).trim()); });
            dl.innerHTML = Array.from(names).filter(Boolean).sort((a, b) => a.localeCompare(b, 'fr'))
                .map(n => `<option value="${escapeOptionAttr(n)}"></option>`).join('');
        }

        function updateRvBonsLiveSoldes() {
            const tbody = document.getElementById('rvBonsTableBody');
            if (!tbody) return;
            const selectedBon = document.querySelector('#rvBonsTableBody input[name="rv_bon_choix"]:checked')?.value
                || document.getElementById('rv_bon_num')?.value || '';
            const saisi = parseFloat(document.getElementById('rv_montant_reg')?.value || 0) || 0;
            const excludeId = editingReglementVenteId || null;
            const fmt = (typeof formatMoneyFr === 'function') ? formatMoneyFr : (v) => (parseFloat(v) || 0).toFixed(2) + ' MAD';
            tbody.querySelectorAll('tr[data-bon]').forEach(tr => {
                const bon = String(tr.getAttribute('data-bon') || '');
                const total = parseFloat(tr.getAttribute('data-montant') || 0) || 0;
                let paye = getMontantPayeBonVente(bon, excludeId);
                if (selectedBon && bon === selectedBon) paye = Math.round((paye + saisi) * 100) / 100;
                const solde = Math.round((total - paye) * 100) / 100;
                const payeEl = tr.querySelector('[data-col="paye"]');
                const soldeEl = tr.querySelector('[data-col="solde"]');
                if (payeEl) payeEl.textContent = fmt(paye);
                if (soldeEl) soldeEl.innerHTML = '<strong>' + escHtml(fmt(solde)) + '</strong>';
            });
        }

        function renderRvBonsTable(nomClient, includeBon = '') {
            const tbody = document.getElementById('rvBonsTableBody');
            if (!tbody) return;
            const bons = getBonsVenteNonSoldesForClient(nomClient, includeBon);
            if (!bons.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="fournisseur-empty">' + (nomClient ? 'Aucun bon non soldé' : 'Sélectionnez un client') + '</td></tr>';
                return;
            }
            const fmt = (typeof formatMoneyFr === 'function') ? formatMoneyFr : (v) => (parseFloat(v) || 0).toFixed(2) + ' MAD';
            const selected = document.getElementById('rv_bon_num')?.value || includeBon || '';
            tbody.innerHTML = bons.map(c => {
                const paye = getMontantPayeBonVente(c.bon, editingReglementVenteId);
                const solde = Math.round(((parseFloat(c.total) || 0) - paye) * 100) / 100;
                const checked = selected && String(c.bon) === String(selected) ? 'checked' : '';
                return `<tr class="${checked ? 'selected' : ''}" data-bon="${escapeOptionAttr(c.bon || '')}" data-montant="${escapeOptionAttr(String(c.total || 0))}">
                    <td><input type="radio" name="rv_bon_choix" value="${escapeOptionAttr(c.bon || '')}" ${checked} ${reglementVenteReadonly ? 'disabled' : ''}></td>
                    <td>${escHtml(c.bon)}</td>
                    <td>${formatDateFr(c.date_cmd)}</td>
                    <td>${escHtml(fmt(c.total))}</td>
                    <td data-col="paye">${escHtml(fmt(paye))}</td>
                    <td data-col="solde"><strong>${escHtml(fmt(solde))}</strong></td>
                </tr>`;
            }).join('');
            tbody.querySelectorAll('input[name="rv_bon_choix"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    applySelectedBonToReglementVenteForm(radio.value);
                    updateRvBonsLiveSoldes();
                });
            });
            updateRvBonsLiveSoldes();
        }

        function applySelectedBonToReglementVenteForm(bonOverride = null) {
            const bon = bonOverride != null
                ? String(bonOverride)
                : (document.querySelector('#rvBonsTableBody input[name="rv_bon_choix"]:checked')?.value
                    || document.getElementById('rv_bon_num')?.value || '');
            const c = (commandesVentes || []).find(x => String(x.bon || '') === String(bon)) || null;
            if (!c) {
                document.getElementById('rv_bon_num').value = '';
                document.getElementById('rv_montant_bon').value = '';
                return;
            }
            document.getElementById('rv_bon_num').value = c.bon || '';
            document.getElementById('rv_client').value = c.nom_client || document.getElementById('rv_client').value;
            document.getElementById('rv_montant_bon').value = c.total || 0;
            const solde = getSoldeRestantBonVente(c, editingReglementVenteId);
            const montantReg = document.getElementById('rv_montant_reg');
            if (montantReg && (!montantReg.value || montantReg.value === '0' || montantReg.value === '0.00')) {
                montantReg.value = Math.max(0, solde).toFixed(2);
            }
            document.querySelectorAll('#rvBonsTableBody tr').forEach(tr => {
                tr.classList.toggle('selected', tr.getAttribute('data-bon') === String(c.bon));
            });
        }

        function refreshReglementVentesKpis() {
            loadReglementsVentes();
            const regs = reglementsVentes || [];
            const countType = (prefix) => regs.filter(r =>
                typeof isReglementType === 'function'
                    ? isReglementType(r.type_reg, prefix)
                    : String(r.type_reg || '').toLowerCase().startsWith(prefix.toLowerCase())
            ).length;
            const sumType = (prefix) => regs
                .filter(r => typeof isReglementType === 'function' ? isReglementType(r.type_reg, prefix) : String(r.type_reg || '').toLowerCase().startsWith(prefix.toLowerCase()))
                .reduce((s, r) => s + (parseFloat(r.montant_reg) || 0), 0);
            const fmt = (typeof formatKpiMoney === 'function') ? formatKpiMoney : (v) => (parseFloat(v) || 0).toFixed(2) + ' MAD';
            const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = fmt(v); };
            const setBadge = (id, n) => { const el = document.getElementById(id); if (el) el.textContent = String(n); };
            set('rvKpiTotalChq', sumType('Chq'));
            set('rvKpiTotalEff', sumType('Eff'));
            set('rvKpiTotalEsp', sumType('Esp'));
            set('rvKpiTotalVir', sumType('Vir'));
            setBadge('rvKpiBadgeChq', countType('Chq'));
            setBadge('rvKpiBadgeEff', countType('Eff'));
            setBadge('rvKpiBadgeEsp', countType('Esp'));
            setBadge('rvKpiBadgeVir', countType('Vir'));
        }

        function renderReglementsVentesTable() {
            if (!reglementsVenteTableBody) return;
            loadReglementsVentes();
            refreshReglementVentesKpis();
            if (!reglementsVentes.length) {
                reglementsVenteTableBody.innerHTML = '<tr><td colspan="9" class="fournisseur-empty">Aucun réglement enregistré</td></tr>';
                return;
            }
            const fmt = (typeof formatMoneyFr === 'function') ? formatMoneyFr : (v) => (parseFloat(v) || 0).toFixed(2);
            reglementsVenteTableBody.innerHTML = reglementsVentes.map(r => `
                <tr>
                    <td>${formatDateFr(r.date)}</td>
                    <td><strong>${escHtml(r.ref)}</strong></td>
                    <td>${escHtml(r.client)}</td>
                    <td>${escHtml(r.bon)}</td>
                    <td>${escHtml(fmt(r.montant))}</td>
                    <td>${escHtml(r.type_reg)}</td>
                    <td>${escHtml(r.num_reg)}</td>
                    <td><strong>${escHtml(fmt(r.montant_reg))}</strong></td>
                    <td class="col-actions">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-edit btn-rg-action" data-rv-edit="${escHtml(r.id)}" title="Modifier"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                            <button type="button" class="btn-icon-row btn-icon-delete btn-rg-action" data-rv-del="${escHtml(r.id)}" title="Supprimer"><svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button>
                        </span>
                    </td>
                </tr>
            `).join('');
            reglementsVenteTableBody.querySelectorAll('[data-rv-edit]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const item = reglementsVentes.find(x => x.id === btn.dataset.rvEdit);
                    if (item) showReglementVenteForm(item, false);
                });
            });
            reglementsVenteTableBody.querySelectorAll('[data-rv-del]').forEach(btn => {
                btn.addEventListener('click', () => deleteReglementVente(btn.dataset.rvDel));
            });
        }

        function showReglementVenteConsult() {
            if (reglementVenteFormPanel) reglementVenteFormPanel.classList.add('hidden');
            if (reglementVenteConsultPanel) reglementVenteConsultPanel.classList.remove('hidden');
            renderReglementsVentesTable();
        }

        function showReglementVenteForm(item = null, readonly = false) {
            reglementVenteReadonly = !!readonly;
            editingReglementVenteId = item?.id || null;
            if (reglementVenteConsultPanel) reglementVenteConsultPanel.classList.add('hidden');
            if (reglementVenteFormPanel) reglementVenteFormPanel.classList.remove('hidden');
            loadCommandesVentesStore();
            loadClients();
            fillReglementVenteClientDatalist();

            const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
            if (item) {
                set('rv_date', item.date);
                set('rv_ref', item.ref);
                set('rv_client', item.client);
                set('rv_type', item.type_reg);
                set('rv_num', item.num_reg);
                set('rv_banque', item.banque);
                set('rv_tire', item.tire);
                set('rv_montant_reg', item.montant_reg);
                set('rv_date_encaiss', item.date_encaiss);
                set('rv_bon_num', item.bon);
                set('rv_montant_bon', item.montant);
                renderRvBonsTable(item.client, item.bon);
            } else {
                document.getElementById('ficheReglementVenteForm')?.reset();
                set('rv_date', todayIsoDate());
                set('rv_ref', nextReglementVenteRef());
                set('rv_bon_num', '');
                set('rv_montant_bon', '');
                renderRvBonsTable('');
            }
            const title = document.getElementById('reglementVenteFormTitle');
            if (title) title.textContent = item ? (readonly ? 'Consulter Réglement' : 'Modifier Réglement') : 'Réglement Vente';
            const valider = document.getElementById('validerReglementVenteBtn');
            if (valider) valider.classList.toggle('hidden', readonly);
            ['rv_date','rv_client','rv_type','rv_num','rv_banque','rv_tire','rv_montant_reg','rv_date_encaiss'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = readonly;
            });
        }

        function collectReglementVenteForm() {
            applySelectedBonToReglementVenteForm();
            return {
                id: editingReglementVenteId || ('rv_' + Date.now()),
                date: document.getElementById('rv_date')?.value || '',
                ref: document.getElementById('rv_ref')?.value || nextReglementVenteRef(),
                client: document.getElementById('rv_client')?.value?.trim() || '',
                bon: document.getElementById('rv_bon_num')?.value || '',
                montant: document.getElementById('rv_montant_bon')?.value || '',
                type_reg: document.getElementById('rv_type')?.value || '',
                num_reg: (document.getElementById('rv_num')?.value || '').trim(),
                banque: document.getElementById('rv_banque')?.value || '',
                tire: (document.getElementById('rv_tire')?.value || '').trim(),
                montant_reg: document.getElementById('rv_montant_reg')?.value || '',
                date_encaiss: document.getElementById('rv_date_encaiss')?.value || '',
            };
        }

        function validateReglementVente(data) {
            if (!data.date) { alert('La date est obligatoire.'); return false; }
            if (!data.client) { alert('Sélectionnez un client.'); return false; }
            if (!data.bon) { alert('Sélectionnez le bon de vente non soldé à régler.'); return false; }
            if (!data.type_reg) { alert('Le type de réglement est obligatoire.'); return false; }
            if (!data.montant_reg || parseFloat(data.montant_reg) <= 0) {
                alert('Le montant du réglement est obligatoire.');
                return false;
            }
            return true;
        }

        function markBonVentePayeIfFullySettled(bon) {
            if (!bon) return;
            const idx = commandesVentes.findIndex(c => c.bon === bon);
            if (idx < 0) return;
            const totalBon = parseFloat(commandesVentes[idx].total || 0) || 0;
            const totalReg = reglementsVentes
                .filter(r => r.bon === bon)
                .reduce((s, r) => s + (parseFloat(r.montant_reg) || 0), 0);
            if (totalReg + 0.001 >= totalBon) {
                commandesVentes[idx].paye = true;
                persistCommandesVentes();
            }
        }

        function saveReglementVenteFromForm() {
            if (reglementVenteReadonly) return false;
            const data = collectReglementVenteForm();
            if (!validateReglementVente(data)) return false;
            const idx = reglementsVentes.findIndex(r => r.id === data.id);
            if (idx >= 0) reglementsVentes[idx] = data;
            else reglementsVentes.unshift(data);
            saveReglementsVentes();
            markBonVentePayeIfFullySettled(data.bon);
            if (typeof refreshDashboardAnalytics === 'function') refreshDashboardAnalytics();
            if (balanceClientsView && !balanceClientsView.classList.contains('hidden')) renderBalanceClientsTable();
            showReglementVenteConsult();
            return true;
        }

        function deleteReglementVente(id) {
            if (!confirm('Supprimer ce réglement ?')) return;
            reglementsVentes = reglementsVentes.filter(r => r.id !== id);
            saveReglementsVentes();
            renderReglementsVentesTable();
            if (typeof refreshDashboardAnalytics === 'function') refreshDashboardAnalytics();
        }

        function openReglementVentes() {
            loadReglementsVentes();
            loadCommandesVentesStore();
            showReglementVenteConsult();
        }

        document.getElementById('ajouterReglementVenteBtn')?.addEventListener('click', () => showReglementVenteForm(null, false));
        document.getElementById('fermerReglementVenteForm')?.addEventListener('click', () => showReglementVenteConsult());
        document.getElementById('fermerReglementVenteConsultBtn')?.addEventListener('click', () => {
            document.querySelector('.nav-item[data-view="dashboard"]')?.click();
        });
        document.getElementById('ficheReglementVenteForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            saveReglementVenteFromForm();
        });
        document.getElementById('rv_client')?.addEventListener('change', () => {
            const nom = document.getElementById('rv_client')?.value || '';
            document.getElementById('rv_bon_num').value = '';
            document.getElementById('rv_montant_bon').value = '';
            renderRvBonsTable(nom);
        });
        document.getElementById('rv_montant_reg')?.addEventListener('input', updateRvBonsLiveSoldes);

        /* ── Balance Client ── */
        const balanceClientsView = document.getElementById('balanceClientsView');
        const balanceClientsTableBody = document.getElementById('balanceClientsTableBody');
        let balanceClientsRows = [];

        function balanceKeyFromClient(c) {
            return 'c:' + (String(c.id || '').trim().toLowerCase() || normalizeClientNom(c.nom) || String(c.id || ''));
        }

        function buildBalanceClientsRows() {
            try { loadCommandesVentesStore(); } catch (e) {}
            try { loadReglementsVentes(); } catch (e) {}
            const map = new Map();
            const moneyCents = (v) => (typeof toCents === 'function' ? toCents(v) : Math.round((parseFloat(v) || 0) * 100));
            const cmdAmt = (c) => (typeof commandeMontantExact === 'function' ? commandeMontantExact(c) : (parseFloat(c.total) || 0));

            function ensureRow(key, partial) {
                if (!map.has(key)) {
                    map.set(key, {
                        key,
                        id: partial.id || '—',
                        nom: partial.nom || '—',
                        montantCents: 0,
                        payeCents: 0,
                        client: partial.client || null,
                    });
                }
                const row = map.get(key);
                if (partial.nom && (row.nom === '—' || !row.nom)) row.nom = partial.nom;
                if (partial.id && row.id === '—') row.id = partial.id;
                if (partial.client) row.client = partial.client;
                return row;
            }

            (clients || []).forEach(c => {
                ensureRow(balanceKeyFromClient(c), { id: c.id, nom: c.nom, client: c });
            });

            (commandesVentes || []).forEach(c => {
                const matched = (clients || []).find(cl => commandeBelongsToClient(c, cl));
                const key = matched
                    ? balanceKeyFromClient(matched)
                    : 'n:' + (normalizeClientNom(c.nom_client) || String(c.code_client || '').toLowerCase() || 'inconnu');
                const row = ensureRow(key, {
                    id: matched ? matched.id : (c.code_client || '—'),
                    nom: matched ? matched.nom : (c.nom_client || c.code_client || '—'),
                    client: matched || null,
                });
                row.montantCents += moneyCents(cmdAmt(c));
            });

            (reglementsVentes || []).forEach(r => {
                const matched = (clients || []).find(cl => normalizeClientNom(cl.nom) === normalizeClientNom(r.client));
                const key = matched
                    ? balanceKeyFromClient(matched)
                    : 'n:' + (normalizeClientNom(r.client) || 'inconnu');
                const row = ensureRow(key, {
                    id: matched ? matched.id : '—',
                    nom: matched ? matched.nom : (r.client || '—'),
                    client: matched || null,
                });
                row.payeCents += moneyCents(r.montant_reg);
            });

            balanceClientsRows = Array.from(map.values()).map(r => {
                const montant = (typeof fromCents === 'function' ? fromCents(r.montantCents) : r.montantCents / 100);
                const paye = (typeof fromCents === 'function' ? fromCents(r.payeCents) : r.payeCents / 100);
                const solde = Math.round((montant - paye) * 100) / 100;
                return {
                    key: r.key,
                    id: r.id,
                    nom: r.nom,
                    montant,
                    paye,
                    solde,
                    reliquat: solde,
                    client: r.client,
                };
            }).filter(r => r.montant || r.paye || (r.client && (parseFloat(r.client.solde) || 0)))
              .sort((a, b) => String(a.nom).localeCompare(String(b.nom), 'fr'));
        }

        function formatBalanceMoneyClient(val) {
            if (typeof formatMoneyFr === 'function') return formatMoneyFr(val);
            if (typeof formatMoney === 'function') return formatMoney(val);
            return (parseFloat(val) || 0).toFixed(2) + ' MAD';
        }

        function renderBalanceClientsTable() {
            if (!balanceClientsTableBody) return;
            buildBalanceClientsRows();
            if (!balanceClientsRows.length) {
                balanceClientsTableBody.innerHTML = '<tr><td colspan="6" class="fournisseur-empty">Aucune balance</td></tr>';
                return;
            }
            balanceClientsTableBody.innerHTML = balanceClientsRows.map(r => `
                <tr>
                    <td>${escHtml(r.id)}</td>
                    <td>${escHtml(r.nom)}</td>
                    <td>${escHtml(formatBalanceMoneyClient(r.montant))}</td>
                    <td>${escHtml(formatBalanceMoneyClient(r.paye))}</td>
                    <td class="solde-cell ${soldeClass(r.solde)}">${escHtml(formatBalanceMoneyClient(r.solde))}</td>
                    <td class="solde-cell ${soldeClass(r.reliquat)}">${escHtml(formatBalanceMoneyClient(r.reliquat))}</td>
                </tr>
            `).join('');
        }

        async function openBalanceClients() {
            await loadClients();
            loadCommandesVentesStore();
            loadReglementsVentes();
            renderBalanceClientsTable();
        }

        document.getElementById('fermerBalanceClientsBtn')?.addEventListener('click', () => {
            document.querySelector('.nav-item[data-view="dashboard"]')?.click();
        });
