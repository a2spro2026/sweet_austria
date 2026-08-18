        (function initTableSortArrows() {
            const TABLE_SELECTOR = [
                'table.fournisseur-table',
                'table.achats-commandes-table',
                'table.produits-table',
                'table.stock-table',
                'table.materiels-table'
            ].join(',');
            const SKIP_HEADER = /^(actions?|photo|qr\s*code)$/i;

            function parseSortValue(text) {
                const raw = String(text || '').replace(/\u00a0/g, ' ').trim();
                if (!raw || raw === '—' || raw === '-') return { type: 'empty', value: '' };
                const dateFr = raw.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                if (dateFr) return { type: 'num', value: Date.UTC(+dateFr[3], +dateFr[2] - 1, +dateFr[1]) };
                const iso = raw.match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (iso) return { type: 'num', value: Date.UTC(+iso[1], +iso[2] - 1, +iso[3]) };
                const code = raw.match(/^[A-Za-z]+(\d+)$/);
                if (code) return { type: 'num', value: Number(code[1]) };
                const stripped = raw.replace(/MAD/gi, '').trim();
                const compact = stripped.replace(/\s/g, '');
                if (/^-?\d+([ .]\d{3})+,\d+$/.test(stripped) || /^-?\d+,\d+$/.test(compact)) {
                    return { type: 'num', value: parseFloat(compact.replace(/\./g, '').replace(',', '.')) };
                }
                if (/^-?\d+([ .]\d{3})+$/.test(stripped)) {
                    return { type: 'num', value: parseFloat(compact.replace(/[ .]/g, '')) };
                }
                if (/^-?\d+(\.\d+)?$/.test(compact)) {
                    return { type: 'num', value: parseFloat(compact) };
                }
                return { type: 'str', value: raw.toLowerCase() };
            }

            function cellText(row, colIndex) {
                const cell = row.cells[colIndex];
                if (!cell) return '';
                return (cell.innerText || cell.textContent || '').replace(/\s+/g, ' ').trim();
            }

            function rowGroups(tbody) {
                const rows = Array.from(tbody.rows);
                const groups = [];
                for (let i = 0; i < rows.length; i++) {
                    const spans = Array.from(rows[i].cells).map(c => Number(c.rowSpan) || 1);
                    const span = Math.max(1, ...spans);
                    groups.push(rows.slice(i, i + span));
                    i += span - 1;
                }
                return groups;
            }

            function isEmptyGroup(group) {
                return group.length === 1 && group[0].querySelector('.fournisseur-empty, .achats-commandes-empty, .produits-empty');
            }

            function sortTable(table, colIndex, dir) {
                const tbody = table.tBodies[0];
                if (!tbody || table._sorting) return;
                const groups = rowGroups(tbody);
                const empty = groups.filter(isEmptyGroup);
                const data = groups.filter(g => !isEmptyGroup(g));
                if (!data.length) return;
                table._sorting = true;
                if (table._sortObserver) table._sortObserver.disconnect();
                data.sort((a, b) => {
                    const va = parseSortValue(cellText(a[0], colIndex));
                    const vb = parseSortValue(cellText(b[0], colIndex));
                    let cmp = 0;
                    if (va.type === 'empty' && vb.type !== 'empty') cmp = 1;
                    else if (vb.type === 'empty' && va.type !== 'empty') cmp = -1;
                    else if (va.type === 'num' && vb.type === 'num') cmp = va.value - vb.value;
                    else cmp = String(va.value).localeCompare(String(vb.value), 'fr', { numeric: true, sensitivity: 'base' });
                    return dir === 'desc' ? -cmp : cmp;
                });
                const frag = document.createDocumentFragment();
                data.concat(empty).forEach(group => group.forEach(row => frag.appendChild(row)));
                tbody.appendChild(frag);
                table._sorting = false;
                if (table._sortObserver) table._sortObserver.observe(tbody, { childList: true });
            }

            function enhanceTable(table) {
                if (!table || !table.tHead || table.dataset.sortReady === '1') return;
                table.dataset.sortReady = '1';
                const headerRow = table.tHead.rows[0];
                if (!headerRow) return;
                Array.from(headerRow.cells).forEach((th, index) => {
                    if (th.classList.contains('col-actions') || th.classList.contains('th-sortable')) return;
                    const label = (th.textContent || '').replace(/\s+/g, ' ').trim();
                    if (!label || SKIP_HEADER.test(label)) return;
                    th.classList.add('th-sortable');
                    th.dataset.sortCol = String(index);
                    th.setAttribute('title', 'Trier : ' + label);
                    if (!th.querySelector('.th-sort-arrows')) {
                        const wrap = document.createElement('span');
                        wrap.className = 'th-sort-label';
                        const text = document.createElement('span');
                        text.textContent = label;
                        th.textContent = '';
                        const arrows = document.createElement('span');
                        arrows.className = 'th-sort-arrows';
                        arrows.setAttribute('aria-hidden', 'true');
                        arrows.innerHTML = '<span class="arr-up"></span><span class="arr-down"></span>';
                        wrap.appendChild(text);
                        wrap.appendChild(arrows);
                        th.appendChild(wrap);
                    }
                    th.addEventListener('click', event => {
                        if (event.target.closest('button, a, input, select')) return;
                        const current = th.classList.contains('is-asc') ? 'asc' : (th.classList.contains('is-desc') ? 'desc' : '');
                        const next = current === 'asc' ? 'desc' : 'asc';
                        headerRow.querySelectorAll('th.th-sortable').forEach(cell => {
                            cell.classList.remove('is-asc', 'is-desc');
                        });
                        th.classList.add(next === 'asc' ? 'is-asc' : 'is-desc');
                        table.dataset.sortCol = String(index);
                        table.dataset.sortDir = next;
                        sortTable(table, index, next);
                    });
                });
                if (table.tBodies[0] && !table._sortObserver) {
                    table._sortObserver = new MutationObserver(() => {
                        if (table._sorting) return;
                        const col = Number(table.dataset.sortCol);
                        const dir = table.dataset.sortDir;
                        if (!Number.isFinite(col) || !dir) return;
                        sortTable(table, col, dir);
                    });
                    table._sortObserver.observe(table.tBodies[0], { childList: true });
                }
            }

            function enhanceAll() {
                document.querySelectorAll(TABLE_SELECTOR).forEach(enhanceTable);
            }

            enhanceAll();
            const rootObserver = new MutationObserver(mutations => {
                for (const mutation of mutations) {
                    mutation.addedNodes.forEach(node => {
                        if (!(node instanceof Element)) return;
                        if (node.matches?.(TABLE_SELECTOR)) enhanceTable(node);
                        node.querySelectorAll?.(TABLE_SELECTOR).forEach(enhanceTable);
                    });
                }
            });
            rootObserver.observe(document.body, { childList: true, subtree: true });
        })();
