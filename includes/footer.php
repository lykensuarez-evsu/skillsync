<?php
// Footer and Closing Tags
?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="card soft" style="box-shadow:none;">
                <strong><?php echo isset($project) ? htmlspecialchars($project['name']) : 'SkillSync'; ?></strong> - Academic prototype for EVSU internship matching.<br>
                Now using MySQL database for persistent data storage. All changes are automatically saved.
            </div>
        </div>
    </footer>

    <script>
        // Database is now being used for all data storage
        // The app.js will load data from the API on initialization
        console.log('SkillSync initialized with MySQL database backend');
    </script>
    <script src="assets/js/app.js"></script>
</body>
</html>
