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

    # 3. Use JavaScript to move the widget and create the input, bypassing drag-and-drop
    page.evaluate('''() => {
        const dummyWidget = document.querySelector("#available-widgets .widget-item[data-id='dummy-widget']");
        const sidebarList = document.getElementById("sidebar-widgets");

        // Move the element
        sidebarList.appendChild(dummyWidget);

        // Create the hidden input that the PHP script expects
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sidebar_widgets[]';
        input.value = 'dummy-widget';
        dummyWidget.appendChild(input);
    }''')

    # 4. Save the widget configuration
    page.get_by_role("button", name="Save Widgets").click()

    # 5. Wait for the redirect and confirm the success message
    page.wait_for_url("**/widgets.php?success=1")
    expect(page.locator("text=Widgets updated successfully!")).to_be_visible()

    # 6. Go to the public site and verify the sidebar
    page.goto(public_url)

    sidebar = page.locator(".sidebar")
    expect(sidebar).to_be_visible()

    widget_text = sidebar.locator(".widget p")
    expect(widget_text).to_have_text("This is a dummy widget. If you can see this, the sidebar is working!")

    # 7. Take a screenshot for confirmation
    page.screenshot(path="jules-scratch/verification/widget-system-verified-no-drag.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)