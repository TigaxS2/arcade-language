/**
 * ARCADE LANGUAGE - ACADEMIC CORE JS V5.0
 */

const ArcadeUI = (() => {
    // --- PRIVATE METHODS ---
    const _createToastContainer = () => {
        if (document.getElementById('toast-container')) return;
        const container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    };

    // --- PUBLIC API ---
    return {
        init: () => {
            console.log("Terminal Acadêmico: Ativo.");
            _createToastContainer();
            ArcadeUI.handleURLMessages();
            ArcadeUI.initModals();
        },

        showToast: (message, type = 'info') => {
            _createToastContainer();
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = { success: '✅', error: '⚠️', info: 'ℹ️' };
            toast.innerHTML = `<span>${icons[type] || 'ℹ️'}</span> <span>${message}</span>`;
            
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        },

        handleURLMessages: () => {
            const params = new URLSearchParams(window.location.search);
            if (params.has('msg')) {
                ArcadeUI.showToast(params.get('msg'), params.get('type') || 'info');
                // Limpa a URL sem recarregar a página
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        },

        initModals: () => {
            const modal = document.getElementById('custom-modal');
            if (!modal) return;

            const btnConfirm = document.getElementById('modal-confirm');
            const btnCancel = document.getElementById('modal-cancel');
            let _callback = null;

            window.customConfirm = (title, msg, callback) => {
                document.getElementById('modal-title').textContent = title;
                document.getElementById('modal-message').textContent = msg;
                modal.style.display = 'flex';
                _callback = callback;
            };

            btnConfirm.onclick = () => { modal.style.display = 'none'; if(_callback) _callback(true); };
            btnCancel.onclick = () => { modal.style.display = 'none'; if(_callback) _callback(false); };

            // Intercepta exclusões
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = (e) => {
                    e.preventDefault();
                    customConfirm('⚠️ CONFIRMAÇÃO', 'Deseja excluir este registro permanentemente?', (confirmed) => {
                        if (confirmed) window.location.href = btn.getAttribute('href');
                    });
                };
            });
        }
    };
})();

document.addEventListener('DOMContentLoaded', ArcadeUI.init);
