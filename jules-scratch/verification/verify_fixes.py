from playwright.sync_api import sync_playwright, expect
import os

def run_verification(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    # Use the local PHP server
    base_url = "http://localhost:8080/admin"

    # 0. Login first
    login_url = f"{base_url}/index.php"
    page.goto(login_url)
    page.get_by_label("Password").fill("password")
    page.get_by_role("button", name="Login").click()

    # 1. Verify Edit Page
    # Navigate to the edit page for 'hello-world' post
    edit_url = f"{base_url}/edit.php?slug=hello-world"
    page.goto(edit_url)

    # Check that the page title is "Edit Post"
    expect(page).to_have_title("Edit Post")

    # Take a screenshot to verify the styling
    page.screenshot(path="jules-scratch/verification/edit-page-styled.png")

    # 2. Verify Delete Page
    # Navigate to the delete confirmation page
    delete_url = f"{base_url}/delete.php?slug=hello-world"
    page.goto(delete_url)

    # Check that the page title is "Confirm Deletion"
    expect(page).to_have_title("Confirm Deletion")

    # Take a screenshot of the delete confirmation page
    page.screenshot(path="jules-scratch/verification/delete-confirmation-page.png")

    # Click the delete button
    page.get_by_role("button", name="Yes, Delete This Post").click()

    # Assert that the page redirects to the dashboard with a success message
    expect(page).to_have_url(f"{base_url}/dashboard.php?success=Post%20deleted%20successfully.")

    # Take a screenshot of the dashboard showing the success message
    page.screenshot(path="jules-scratch/verification/delete-success.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)