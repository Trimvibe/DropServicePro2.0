document.addEventListener('DOMContentLoaded', function() {
    const serviceSearchForm = document.getElementById('serviceSearchForm');
    const servicesList = document.querySelector('.services-list');

    // Mock data for services
    const services = [
        { id: 1, title: 'Web Development', description: 'Custom website development', price: 500 },
        { id: 2, title: 'Logo Design', description: 'Professional logo design', price: 100 },
        { id: 3, title: 'Content Writing', description: 'SEO-optimized content writing', price: 50 },
        // Add more services as needed
    ];

    function displayServices(servicesArray) {
        servicesList.innerHTML = '';
        servicesArray.forEach(service => {
            const serviceElement = document.createElement('div');
            serviceElement.classList.add('service-item');
            serviceElement.innerHTML = `
                <h3>${service.title}</h3>
                <p>${service.description}</p>
                <p>Price: $${service.price}</p>
                <button onclick="orderService(${service.id})">Order Now</button>
            `;
            servicesList.appendChild(serviceElement);
        });
    }

    serviceSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const searchTerm = document.getElementById('serviceSearchInput').value.toLowerCase();
        const filteredServices = services.filter(service => 
            service.title.toLowerCase().includes(searchTerm) || 
            service.description.toLowerCase().includes(searchTerm)
        );
        displayServices(filteredServices);
    });

    // Initial display of all services
    displayServices(services);
});

function orderService(serviceId) {
    // Implement order functionality here
    alert(`Service with ID ${serviceId} ordered!`);
    // In a real application, this would typically involve adding the service to a cart or initiating a checkout process
}
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown hover effect for desktop
    if (window.innerWidth > 991) {
        document.querySelectorAll('.navbar .dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('mouseenter', function() {
                this.querySelector('.dropdown-toggle').click();
            });
            
            dropdown.addEventListener('mouseleave', function() {
                this.querySelector('.dropdown-toggle').click();
            });
        });
    }

    // Active link handling
    const currentLocation = location.href;
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        if (link.href === currentLocation) {
            link.classList.add('active');
        }
    });
});
