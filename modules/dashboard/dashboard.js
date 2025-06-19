const dashboard = {
	currentPage: 1,
	query: '',
	type: '',
	
	init() {
		this.bindEvents();
		this.fetchProjects();
	},
	
	bindEvents() {
		document.getElementById('searchInput').addEventListener('input', e => {
			this.query = e.target.value;
			this.currentPage = 1;
			this.fetchProjects();
		});
		
		document.getElementById('typeFilter').addEventListener('change', e => {
			this.type = e.target.value;
			this.currentPage = 1;
			this.fetchProjects();
		});
		
		document.addEventListener('click', e => {
			if (e.target.classList.contains('page-link')) {
				this.currentPage = parseInt(e.target.dataset.page);
				this.fetchProjects();
			}
		});
	},
	
	fetchProjects() {
		fetch(`modules/dashboard/data.php?query=${encodeURIComponent(this.query)}&type=${this.type}&page=${this.currentPage}`)
			.then(res => res.json())
			.then(data => this.render(data));
	},
	
	render(data) {
		const container = document.getElementById('dashboardGrid');
		container.innerHTML = '';
		
		data.projects.forEach(p => {
			const card = document.createElement('div');
			card.className = 'col-6 col-sm-4 col-md-3 col-xl-2';
			card.innerHTML = `
        <div class="card text-center shadow-sm border-0 h-100" style="background-color: #f9f9f9">
          <div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
            <iconify-icon icon="${p.icon}" width="40" height="40"></iconify-icon>
          </div>
          <div class="card-footer bg-white fw-semibold text-truncate d-flex justify-content-between align-items-center">
            <a href="${p.link}" class="text-dark text-decoration-none flex-grow-1 text-truncate" target="_blank">${p.name}</a>
            ${p.admin ? `<a href="${p.admin}" target="_blank" class="ms-2 text-muted"><iconify-icon icon="mdi:cog" width="16" height="16"></iconify-icon></a>` : ''}
          </div>
        </div>
      `;
			container.appendChild(card);
		});
		
		this.renderPagination(data.page, data.total, data.limit);
	},
	
	renderPagination(current, totalItems, limit) {
		const totalPages = Math.ceil(totalItems / limit);
		const container = document.getElementById('pagination');
		container.innerHTML = '';
		
		for (let p = 1; p <= totalPages; p++) {
			const btn = document.createElement('button');
			btn.className = `btn btn-sm ${p === current ? 'btn-primary' : 'btn-outline-secondary'} page-link me-1`;
			btn.dataset.page = p;
			btn.textContent = p;
			container.appendChild(btn);
		}
	}
};

document.addEventListener('DOMContentLoaded', () => dashboard.init());
