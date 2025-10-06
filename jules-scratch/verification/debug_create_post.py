from playwright.sync_api import sync_playwright, expect
import os

def run_debug_script(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    base_url = "http://localhost:8080"
    admin_url = f"{base_url}/admin"

    # 1. Log in
    login_url = f"{admin_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 2. Go to the Create Post page
    create_page_url = f"{admin_url}/create.php"
    page.goto(create_page_url)

    # 3. Fill out the form
    page.get_by_label("Post Title").fill("Debug Test Post")
    page.get_by_label("Content (in Markdown)").fill("This is some debug content.")

    # 4. Submit the form and capture the result
    page.get_by_role("button", name="Save and Publish").click()

    # Wait for the page to load after submission
    page.wait_for_load_state('networkidle')

    # 5. Print the content of the page to see the error
    error_content = page.content()
    print("--- Page Content After Post Submission ---")
    print(error_content)
    print("----------------------------------------")

    browser.close()

with sync_playwright() as playwright:
    run_debug_script(playwright)