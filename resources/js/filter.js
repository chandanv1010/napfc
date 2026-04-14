document.addEventListener('DOMContentLoaded', function() {
    var btnLoadMore = document.querySelector('.btn-loadmore');
    var initialLimit = 6;

    function refreshLoadMore() {
        var activePane = document.querySelector('.tab-pane.active');
        if (!activePane) return;

        var items = activePane.querySelectorAll('.product-grid-item');
        var visibleItems = Array.from(items).filter(item => item.style.display !== 'none');

        if (visibleItems.length === 0) {
            if (btnLoadMore) btnLoadMore.style.display = 'none';
            return;
        }

        if (!activePane.dataset.loadedAll) {
            visibleItems.forEach(function(item, index) {
                if (index >= initialLimit) {
                    item.classList.add('hide-loadmore');
                } else {
                    item.classList.remove('hide-loadmore');
                }
            });

            if (visibleItems.length > initialLimit) {
                if (btnLoadMore) btnLoadMore.style.display = 'inline-block';
            } else {
                if (btnLoadMore) btnLoadMore.style.display = 'none';
            }
        } else {
            visibleItems.forEach(item => item.classList.remove('hide-loadmore'));
            if (btnLoadMore) btnLoadMore.style.display = 'none';
        }
    }

    // --- 1. Tab Switching Logic ---
    var tabItems = document.querySelectorAll('.category-nav-item');
    var tabPanes = document.querySelectorAll('.tab-pane');

    tabItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var target = this.dataset.target;
            tabItems.forEach(function(i) { i.classList.remove('active'); });
            this.classList.add('active');

            tabPanes.forEach(function(p) {
                if (p.id === target) {
                    p.classList.add('active');
                } else {
                    p.classList.remove('active');
                }
            });
            refreshLoadMore();
        });
    });

    // --- 2. Load More Action ---
    if (btnLoadMore) {
        btnLoadMore.addEventListener('click', function(e) {
            e.preventDefault();
            var activePane = document.querySelector('.tab-pane.active');
            if (activePane) {
                activePane.dataset.loadedAll = "true";
                var items = activePane.querySelectorAll('.product-grid-item');
                items.forEach(item => item.classList.remove('hide-loadmore'));
                this.style.display = 'none';
            }
        });
    }

    var filterPrice = document.getElementById('filter-price');
    var filterSort = document.getElementById('filter-sort');
    var filterKeyword = document.getElementById('filter-keyword');
    var btnSearch = document.getElementById('btn-filter-search');
    var btnReset = document.getElementById('btn-filter-reset');

    function applyFilters() {
        var activePane = document.querySelector('.tab-pane.active');
        if (!activePane) return;

        activePane.dataset.loadedAll = ""; 

        var items = activePane.querySelectorAll('.product-grid-item');
        var priceRange = filterPrice ? filterPrice.value : '';
        var keyword = filterKeyword ? filterKeyword.value.toLowerCase().trim() : '';
        var minPrice = 0, maxPrice = Infinity;

        if (priceRange) {
            var parts = priceRange.split('-');
            minPrice = parseInt(parts[0]);
            maxPrice = parseInt(parts[1]);
        }

        items.forEach(function(item) {
            var itemPrice = parseInt(item.dataset.price) || 0;
            var itemName = item.dataset.name || '';
            var itemCode = item.dataset.code || '';
            var show = true;

            if (priceRange && (itemPrice < minPrice || itemPrice > maxPrice)) show = false;
            
            if (keyword) {
                var cleanKeyword = keyword.replace('#', '');
                if (itemName.indexOf(cleanKeyword) === -1 && itemCode.indexOf(cleanKeyword) === -1) {
                    show = false;
                }
            }

            item.style.display = show ? '' : 'none';
        });

        // Sort logic
        var visibleItems = Array.from(items).filter(item => item.style.display !== 'none');
        var sortVal = filterSort ? filterSort.value : '';
        if (sortVal && visibleItems.length > 1) {
            var parent = visibleItems[0].parentNode;
            visibleItems.sort(function(a, b) {
                if (sortVal === 'price_asc') return (parseInt(a.dataset.price)||0) - (parseInt(b.dataset.price)||0);
                if (sortVal === 'price_desc') return (parseInt(b.dataset.price)||0) - (parseInt(a.dataset.price)||0);
                if (sortVal === 'newest') return (parseInt(b.dataset.created)||0) - (parseInt(a.dataset.created)||0);
                return 0;
            });
            visibleItems.forEach(function(item) { parent.appendChild(item); });
        }

        refreshLoadMore();
    }

    if (filterPrice) filterPrice.addEventListener('change', applyFilters);
    if (filterSort) filterSort.addEventListener('change', applyFilters);
    if (btnSearch) btnSearch.addEventListener('click', applyFilters);
    if (filterKeyword) filterKeyword.addEventListener('keyup', function(e) { if(e.keyCode === 13) applyFilters(); });

    if (btnReset) {
        btnReset.addEventListener('click', function() {
            if (filterPrice) filterPrice.value = '';
            if (filterSort) filterSort.value = '';
            if (filterKeyword) filterKeyword.value = '';
            var activePane = document.querySelector('.tab-pane.active');
            if (activePane) activePane.dataset.loadedAll = "";
            document.querySelectorAll('.product-grid-item').forEach(function(item) { 
                item.style.display = ''; 
                item.classList.remove('hide-loadmore');
            });
            refreshLoadMore();
        });
    }

    refreshLoadMore();
});
