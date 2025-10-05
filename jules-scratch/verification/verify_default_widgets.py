from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    admin_url = f"{base_url}/admin"
    public_url = f"{base_url}/public/index.php"

    # 1. Log in
    login_url = f"{admin_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 2. Go to the Widget management page
    widgets_page_url = f"{admin_url}/widgets.php"
    page.goto(widgets_page_url)
    expect(page.get_by_role("heading", name="Manage Widgets")).to_be_visible()

    # 3. Verify all new widgets are available
    expect(page.locator("#available-widgets .widget-item[data-id='categories']")).to_be_visible()
    expect(page.locator("#available-widgets .widget-item[data-id='search']")).to_be_visible()
    expect(page.locator("#available-widgets .widget-item[data-id='login']")).to_be_visible()

    # 4. Use JavaScript to move all widgets to the sidebar, bypassing drag-and-drop
    page.evaluate('''() => {
        const sidebarList = document.getElementById("sidebar-widgets");
        const availableWidgets = document.querySelectorAll("#available-widgets .widget-item");

        availableWidgets.forEach(widget => {
            // Move the element
            sidebarList.appendChild(widget);

            // Create the hidden input that the PHP script expects
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sidebar_widgets[]';
            input.value = widget.dataset.id;
            widget.appendChild(input);
        });
    }''')

    # 5. Save the widget configuration
    page.get_by_role("button", name="Save Widgets").click()
    page.wait_for_url("**/widgets.php?success=1")
    expect(page.locator("text=Widgets updated successfully!")).to_be_visible()

    # 6. Go to the public site and verify the sidebar widgets
    page.goto(public_url)

    sidebar = page.locator(".sidebar")
    expect(sidebar).to_be_visible()

    # Add specific classes to widgets for easier targeting
    # This is a bit of a hack for verification, ideally the widget render function would do this.
    page.evaluate('''() => {
        document.querySelectorAll('.widget').forEach(widget => {
            const h4 = widget.querySelector('h4');
            if (h4) {
                 const widgetName = h4.textContent.toLowerCase().replace(' / ', '-');
                 widget.classList.add(`widget-${widgetName}`);
            }
        });
    }''')

    # Verify Categories widget
    expect(sidebar.locator("text=Technology")).to_be_visible()

    # Verify Search widget
    expect(sidebar.locator("input[name='s']")).to_be_visible()

    # Verify Login/Admin widget
    expect(sidebar.locator("a[href='../admin/dashboard.php']")).to_have_text("Admin")

    # 7. Take a screenshot for confirmation
    page.screenshot(path="jules-scratch/verification/default-widgets-verified.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)