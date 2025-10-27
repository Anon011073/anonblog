<?php
// widgets/links.php

return [
    'name' => 'Links',
    'description' => 'Displays a list of custom links (editable in the admin).',
    'render' => function() {
        $links_file = __DIR__ . '/../data/links.json';

        if (!file_exists($links_file)) {
            echo '<div class="widget"><h3 class="widget-title">Links</h3><p>No links found.</p></div>';
            return;
        }

        $data = json_decode(file_get_contents($links_file), true);
        $links = $data['links'] ?? [];

        echo '<div class="widget">';
        echo '<h3 class="widget-title">Links</h3>';

        if (empty($links)) {
            echo '<p>No links available.</p>';
        } else {
            echo '<ul class="widget-links">';
            foreach ($links as $link) {
                $name = htmlspecialchars($link['name']);
                $url = htmlspecialchars($link['url']);
                echo "<li><a href=\"{$url}\" target=\"_blank\">{$name}</a></li>";
            }
            echo '</ul>';
        }

        echo '</div>';
    }
];
