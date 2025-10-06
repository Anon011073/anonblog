from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    admin_url = f"{base_url}/admin"
    public_url = f"{base_url}/public/index.php"

    # 1. Verify the default (light) theme
    page.goto(public_url)
    page.wait_for_timeout(1000)
    page.screenshot(path="jules-scratch/verification/light-theme-pico.png")

    # 2. Log in
    login_url = f"{admin_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 3. Switch to the dark theme
    themes_page_url = f"{admin_url}/themes.php"
    page.goto(themes_page_url)
    dark_theme_card = page.locator(".theme-card", has_text="Dark")
    dark_theme_card.get_by_role("link", name="Activate").click()
    expect(page.locator("text=Theme activated successfully!")).to_be_visible()

    # 4. Verify the dark theme and check for debug message
    page.goto(public_url)
    page.wait_for_timeout(1000)

    # Print page content to check for the debug comment
    print(page.content())

    page.screenshot(path="jules-scratch/verification/dark-theme-pico.png")

    # 5. (Cleanup) Switch back to the default theme
    page.goto(themes_page_url)
    default_theme_card = page.locator(".theme-card", has_text="Default")
    default_theme_card.get_by_role("link", name="Activate").click()
    expect(page.locator("text=Theme activated successfully!")).to_be_visible()

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)