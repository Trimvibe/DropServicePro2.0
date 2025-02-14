document.addEventListener('DOMContentLoaded', function() {
    // Job search functionality
    const searchForm = document.querySelector('form');
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Implement job search logic here
    });

    // Job filtering functionality
    const filterButton = document.querySelector('.btn-outline-primary');
    filterButton.addEventListener('click', function() {
        // Implement job filtering logic here
    });

    // Job application functionality
    const applyButtons = document.querySelectorAll('.btn-primary');
    applyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Implement job application logic here
            alert('Application submitted!');
        });
    });
});
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const searchInput = document.querySelector('input[placeholder="Search for jobs..."]');
    const searchButton = document.querySelector('.btn-primary');
    const jobsContainer = document.querySelector('.row.g-4');
    const categorySelect = document.querySelector('select:nth-of-type(1)');
    const experienceSelect = document.querySelector('select:nth-of-type(2)');
    const jobTypeSelect = document.querySelector('select:nth-of-type(3)');

    // Sample jobs data (in a real app, this would come from an API)
    const jobs = [
        {
            title: "Senior Web Developer",
            location: "Remote",
            type: "Full-time",
            salary: "$80,000 - $120,000/year",
            category: "Web Development",
            description: "We're looking for an experienced web developer to join our team..."
        },
        {
            title: "Graphic Designer",
            location: "New York, NY",
            type: "Part-time",
            salary: "$30 - $50/hour",
            category: "Graphic Design",
            description: "Creative graphic designer needed for branding and marketing materials."
        }
        // Add more jobs as needed
    ];

    // Search functionality
    function searchJobs() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categorySelect.value;
        const selectedExperience = experienceSelect.value;
        const selectedJobType = jobTypeSelect.value;

        const filteredJobs = jobs.filter(job => {
            const matchesSearch = job.title.toLowerCase().includes(searchTerm) ||
                                job.description.toLowerCase().includes(searchTerm);
            const matchesCategory = selectedCategory === "Category" || job.category === selectedCategory;
            const matchesType = selectedJobType === "Job Type" || job.type === selectedJobType;

            return matchesSearch && matchesCategory && matchesType;
        });

        displayJobs(filteredJobs);
    }

    // Display jobs function
    function displayJobs(jobsToShow) {
        jobsContainer.innerHTML = ''; // Clear current jobs

        jobsToShow.forEach(job => {
            const jobCard = `
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">${job.title}</h3>
                            <p class="card-text">${job.description}</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-map-marker-alt text-primary me-2"></i>${job.location}</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>${job.type}</li>
                                <li><i class="fas fa-dollar-sign text-primary me-2"></i>${job.salary}</li>
                            </ul>
                            <button class="btn btn-primary">Apply Now</button>
                        </div>
                    </div>
                </div>
            `;
            jobsContainer.innerHTML += jobCard;
        });
    }

    // Add event listeners
    searchInput.addEventListener('input', searchJobs);
    searchButton.addEventListener('click', searchJobs);
    categorySelect.addEventListener('change', searchJobs);
    experienceSelect.addEventListener('change', searchJobs);
    jobTypeSelect.addEventListener('change', searchJobs);

    // Initial display of all jobs
    displayJobs(jobs);

    // Add click handler for Apply Now buttons
    jobsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-primary')) {
            alert('Application feature coming soon!');
        }
    });
});
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
