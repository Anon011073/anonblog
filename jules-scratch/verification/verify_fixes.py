from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    # Use the local PHP server
    base_url = "http://localhost:8080"
    admin_url = f"{base_url}/admin"

    # 0. Login first
    login_url = f"{admin_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 1. Verify Edit and Update Page
    edit_url = f"{admin_url}/edit.php?slug=hello-world"
    page.goto(edit_url)
    expect(page).to_have_title("Edit Post")

    # Update the post content
    page.get_by_label("Content (in Markdown)").fill("This is the updated content.")
    page.get_by_role("button", name="Update Post").click()

    # Assert that the page redirects to the public post page
    public_post_url = f"{base_url}/public/index.php?post=hello-world"
    expect(page).to_have_url(public_post_url)

    # Take a screenshot of the updated post
    page.screenshot(path="jules-scratch/verification/updated-post.png")

    # 2. Verify Delete Page Redirect
    delete_url = f"{admin_url}/delete.php?slug=hello-world"
    page.goto(delete_url)
    expect(page).to_have_title("Confirm Deletion")

    # Click the delete button
    page.get_by_role("button", name="Yes, Delete This Post").click()

    # Assert that the page redirects to the public homepage
    public_home_url = f"{base_url}/public/index.php"
    expect(page).to_have_url(public_home_url)

    # Take a screenshot of the public homepage
    page.screenshot(path="jules-scratch/verification/delete-redirect-homepage.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)