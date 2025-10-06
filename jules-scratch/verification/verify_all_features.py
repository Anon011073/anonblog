from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    admin_url = f"{base_url}/admin"
    public_url = f"{base_url}/public/index.php"

    # --- Admin Setup ---

    # 1. Log in
    page.goto(f"{admin_url}/index.php")
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 2. Configure Settings
    page.goto(f"{admin_url}/settings.php")
    # Set posts per page to 2
    page.get_by_label("Posts Per Page").fill("2")
    # Set pagination to numbered
    page.get_by_label("Pagination Style").select_option("numbered")
    # Set custom footer text
    page.get_by_label("Footer Text").fill("My Custom Footer Text")
    # Enable "Back to Top" link
    page.get_by_label("Show \"Back to Top\" Link").check()
    # Save settings
    page.get_by_role("button", name="Save Settings").click()
    expect(page.locator("text=Settings saved successfully!")).to_be_visible()

    # 3. Activate Search Widget using JavaScript to bypass drag_to issues
    page.goto(f"{admin_url}/widgets.php")
    page.evaluate('''() => {
        const searchWidget = document.querySelector("#available-widgets .widget-item[data-id='search']");
        const sidebarList = document.getElementById("sidebar-widgets");
        if (searchWidget && sidebarList) {
            sidebarList.appendChild(searchWidget);
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sidebar_widgets[]';
            input.value = 'search';
            searchWidget.appendChild(input);
        }
    }''')
    page.get_by_role("button", name="Save Widgets").click()
    expect(page.locator("text=Widgets updated successfully!")).to_be_visible()

    # --- Frontend Verification ---

    # 4. Go to homepage and verify features
    page.goto(public_url)

    # Verify only 2 posts are shown (post limit)
    expect(page.locator(".post-excerpt")).to_have_count(2)

    # Verify featured image is shown for the first post
    first_post_image = page.locator(".post-excerpt img[src*='picsum.photos/id/1']")
    expect(first_post_image).to_be_visible()

    # Verify pagination is present and correct
    pagination = page.locator(".pagination")
    expect(pagination).to_be_visible()
    expect(pagination.get_by_role("link", name="2")).to_be_visible()

    # Verify custom footer text and Back to Top link
    footer = page.locator("footer")
    expect(footer).to_contain_text("My Custom Footer Text")
    expect(footer.get_by_role("link", name="Back to Top")).to_be_visible()

    # Verify search widget has no button
    search_widget_frontend = page.locator(".widget form[action='index.php']")
    expect(search_widget_frontend).to_be_visible()
    expect(search_widget_frontend.locator("button")).not_to_be_visible()

    # 5. Take a screenshot of Page 1
    page.screenshot(path="jules-scratch/verification/all-features-page-1.png")

    # 6. Go to Page 2 and verify
    pagination.get_by_role("link", name="2").click()
    expect(page.locator(".post-excerpt")).to_have_count(1) # Only 1 post on the second page
    expect(page.locator("h2 a", has_text="Test Post 3")).to_be_visible()

    # 7. Take a screenshot of Page 2
    page.screenshot(path="jules-scratch/verification/all-features-page-2.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)