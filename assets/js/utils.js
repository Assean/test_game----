/**
 * assets/js/utils.js — 前端共用工具函數
 */

const Utils = {
    /**
     * 顯示 Toast 通知
     * @param {string} message 訊息內容
     * @param {string} type 類型 (success, error, info)
     */
    showToast: function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 left-1/2 -translate-x-1/2 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest z-[100] animate-fade-in-up border backdrop-blur-md shadow-2xl transition-all duration-300`;
        
        if (type === 'success') {
            toast.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/20');
        } else if (type === 'error') {
            toast.classList.add('bg-red-500/20', 'text-red-400', 'border-red-500/20');
        } else {
            toast.classList.add('bg-secondary/20', 'text-secondary', 'border-secondary/20');
        }
        
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    /**
     * 顯示確認對話框
     * @param {string} title 標題
     * @param {string} message 內容
     * @returns {Promise<boolean>}
     */
    confirm: function(title, message) {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/80 backdrop-blur-sm z-[200] flex items-center justify-center p-6';
            
            const modal = document.createElement('div');
            modal.className = 'bg-bg-dark border border-white/10 p-8 rounded-[40px] max-w-sm w-full text-center animate-fade-in-up';
            modal.innerHTML = `
                <h3 class="text-2xl font-black uppercase mb-4 text-white">${title}</h3>
                <p class="text-white/40 text-sm mb-8">${message}</p>
                <div class="flex gap-4">
                    <button id="confirm-cancel" class="flex-1 py-4 border border-white/10 rounded-2xl text-xs font-black uppercase hover:bg-white/5 transition-all text-white/40">取消</button>
                    <button id="confirm-ok" class="flex-1 py-4 bg-white text-bg-dark rounded-2xl text-xs font-black uppercase hover:bg-accent hover:text-white transition-all">確定</button>
                </div>
            `;
            
            overlay.appendChild(modal);
            document.body.appendChild(overlay);
            
            modal.querySelector('#confirm-cancel').onclick = () => {
                overlay.remove();
                resolve(false);
            };
            modal.querySelector('#confirm-ok').onclick = () => {
                overlay.remove();
                resolve(true);
            };
        });
    },

    /**
     * 顯示 Loading 動畫
     */
    showLoading: function() {
        if (document.getElementById('global-loader')) return;
        const loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'fixed inset-0 bg-bg-dark/50 backdrop-blur-sm z-[300] flex items-center justify-center';
        loader.innerHTML = `
            <div class="w-12 h-12 border-4 border-white/20 border-t-secondary rounded-full animate-spin"></div>
        `;
        document.body.appendChild(loader);
    },

    /**
     * 隱藏 Loading 動畫
     */
    hideLoading: function() {
        const loader = document.getElementById('global-loader');
        if (loader) loader.remove();
    },

    /**
     * 敬請期待通知
     */
    comingSoon: function(e) {
        if (e) e.preventDefault();
        Utils.showToast('敬請期待！此功能正在趕工中... 🚀', 'info');
    }
};

window.Utils = Utils;
