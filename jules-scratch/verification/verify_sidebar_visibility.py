from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    public_url = f"{base_url}/public/index.php"

    # Go to the public site
    page.goto(public_url)

    # Verify that the sidebar is visible
    sidebar = page.locator(".sidebar")
    expect(sidebar).to_be_visible()

    # Verify that the dummy widget's content is present
    widget_text = sidebar.locator(".widget p")
    expect(widget_text).to_have_text("This is a dummy widget. If you can see this, the sidebar is working!")

    # Take a screenshot for confirmation
    page.screenshot(path="jules-scratch/verification/sidebar-visibility-check.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)