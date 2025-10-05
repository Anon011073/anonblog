from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    public_url = f"{base_url}/public/index.php"
    admin_url = f"{base_url}/admin"

    # 1. Verify link is NOT visible when logged out
    page.goto(public_url)
    admin_link = page.get_by_role("link", name="Admin")
    expect(admin_link).not_to_be_visible()

    # 2. Log in
    login_url = f"{admin_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 3. Verify link IS visible when logged in
    page.goto(public_url)
    admin_link = page.get_by_role("link", name="Admin")
    expect(admin_link).to_be_visible()

    # 4. Take a screenshot for confirmation
    page.screenshot(path="jules-scratch/verification/admin-link-visible.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)