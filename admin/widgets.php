<?php
// admin/widgets.php

require_once __DIR__ . '/partials/header.php';
require_once ROOT_PATH . '/src/widgets.php';

// Get all available widgets from the /widgets/ directory
$available_widgets = get_available_widgets();

// Load the current widget configuration
$config_file = ROOT_PATH . '/data/widgets.json';
$widget_config = json_decode(file_get_contents($config_file), true);
$sidebar_widgets = $widget_config['sidebar'] ?? [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted list of active sidebar widgets
    $updated_sidebar_widgets = $_POST['sidebar_widgets'] ?? [];

    // Update the config
    $widget_config['sidebar'] = $updated_sidebar_widgets;

    // Save the updated configuration back to the JSON file
    file_put_contents($config_file, json_encode($widget_config, JSON_PRETTY_PRINT));

    // Refresh the page to show a success message and the updated state
    header('Location: widgets.php?success=1');
    exit;
}
?>

<style>
    .widgets-container {
        display: flex;
        gap: 30px;
    }
    .widget-column {
        flex: 1;
    }
    .widget-list {
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 5px;
        min-height: 200px;
        background-color: #f9f9f9;
    }
    .widget-item {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-radius: 3px;
        cursor: grab;
    }
    .widget-item h4 {
        margin: 0 0 5px 0;
    }
    .widget-item p {
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }
</style>

<div class="page-header">
    <h1>Manage Widgets</h1>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="margin-bottom: 20px; background-color: #d4edda; color: #155724; padding: 15px;">
        Widgets updated successfully!
    </div>
<?php endif; ?>

<form action="widgets.php" method="post">
    <div class="widgets-container">
        <!-- Available Widgets -->
        <div class="widget-column">
            <h2>Available Widgets</h2>
            <div class="widget-list" id="available-widgets">
                <?php foreach ($available_widgets as $id => $widget): ?>
                    <?php if (!in_array($id, $sidebar_widgets)): ?>
                        <div class="widget-item" data-id="<?php echo htmlspecialchars($id); ?>">
                            <h4><?php echo htmlspecialchars($widget['name']); ?></h4>
                            <p><?php echo htmlspecialchars($widget['description']); ?></p>
                            <input type="hidden" name="available[]" value="<?php echo htmlspecialchars($id); ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sidebar Widget Area -->
        <div class="widget-column">
            <h2>Sidebar</h2>
            <div class="widget-list" id="sidebar-widgets">
                <?php foreach ($sidebar_widgets as $id): ?>
                     <?php if (isset($available_widgets[$id])):
                        $widget = $available_widgets[$id];
                     ?>
                        <div class="widget-item" data-id="<?php echo htmlspecialchars($id); ?>">
                            <h4><?php echo htmlspecialchars($widget['name']); ?></h4>
                            <p><?php echo htmlspecialchars($widget['description']); ?></p>
                            <input type="hidden" name="sidebar_widgets[]" value="<?php echo htmlspecialchars($id); ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <hr style="margin: 20px 0;">
    <button type="submit" class="btn">Save Widgets</button>
</form>

<!-- Using SortableJS for drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const availableList = document.getElementById('available-widgets');
    const sidebarList = document.getElementById('sidebar-widgets');

    function updateInputs(listElement, inputName) {
        // Remove old hidden inputs
        listElement.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());

        // Add new hidden inputs based on current order
        listElement.querySelectorAll('.widget-item').forEach(item => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = inputName;
            input.value = item.dataset.id;
            item.appendChild(input);
        });
    }

    new Sortable(availableList, {
        group: 'widgets',
        animation: 150,
        onEnd: function() {
            updateInputs(availableList, 'available[]');
        }
    });

    new Sortable(sidebarList, {
        group: 'widgets',
        animation: 150,
        onEnd: function() {
            updateInputs(sidebarList, 'sidebar_widgets[]');
        }
    });
});
</script>

<?php
require_once __DIR__ . '/partials/footer.php';
?>