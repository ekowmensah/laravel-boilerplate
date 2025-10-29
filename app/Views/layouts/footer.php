            </div>
        </div>
        
        <footer class="footer">
            <div class="container-fluid">
                <div class="d-flex justify-content-between">
                    <span>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</span>
                    <span>Version <?php echo APP_VERSION; ?></span>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- CoreUI JS -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/js/coreui.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    pageLength: <?php echo RECORDS_PER_PAGE; ?>,
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    }
                });
            }
            
            // Sidebar dropdown functionality
            $('.nav-group-toggle').on('click', function(e) {
                e.preventDefault();
                const parent = $(this).closest('.nav-group');
                
                // Close other dropdowns
                $('.nav-group').not(parent).removeClass('show');
                
                // Toggle current dropdown
                parent.toggleClass('show');
            });
            
            // Mobile sidebar toggle
            $('.header-toggler').on('click', function() {
                $('#sidebar').toggleClass('show');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest('.sidebar, .header-toggler').length) {
                        $('#sidebar').removeClass('show');
                    }
                }
            });
            
            // Highlight active menu item
            const currentPath = window.location.pathname;
            $('.nav-link').each(function() {
                const href = $(this).attr('href');
                if (href && currentPath.includes(href.split('?')[0])) {
                    $(this).addClass('active');
                    $(this).closest('.nav-group').addClass('show');
                }
            });
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
    <?php if (isset($extraScripts)): ?>
        <?php echo $extraScripts; ?>
    <?php endif; ?>
</body>
</html>
