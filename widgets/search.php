<?php
// widgets/search.php

return [
    'name' => 'Search',
    'description' => 'Displays a search form for the site.',
    'render' => function() {
        // The form will submit to the main index page using the GET method.
        // The search query will be available in the URL as ?s=...
        $search_query = isset($_GET['s']) ? htmlspecialchars($_GET['s']) : '';

        echo '<form action="index.php" method="get" class="search-form">';
        echo '  <input type="search" name="s" placeholder="Search..." value="' . $search_query . '" required>';
        // The button has been removed. The form submits on "Enter".
        echo '</form>';
    }
];