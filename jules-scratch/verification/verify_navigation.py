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

    # 2. Navigate to the Navigation page and add links
    nav_page_url = f"{admin_url}/navigation.php"
    page.goto(nav_page_url)
    expect(page).to_have_title("Blog Admin")

    # Add a "Home" link
    page.locator("#add-link-btn").click()
    page.locator('input[name="links[0][label]"]').fill("Home")
    page.locator('input[name="links[0][url]"]').fill("index.php")

    # Add an "About" link
    page.locator("#add-link-btn").click()
    page.locator('input[name="links[1][label]"]').fill("About")
    page.locator('input[name="links[1][url]"]').fill("#about")

    # Save the menu and wait for the redirect to complete
    page.get_by_role("button", name="Save Menu").click()
    page.wait_for_url("**/navigation.php?success=1")

    # Now that the page has reloaded, confirm the success message is visible
    expect(page.locator("text=Navigation menu updated successfully!")).to_be_visible()

    # 3. Go to the public site and verify the menu and admin link
    page.goto(public_url)

    # Verify navigation links are visible
    home_link = page.locator('.main-nav').get_by_role('link', name='Home')
    about_link = page.locator('.main-nav').get_by_role('link', name='About')
    expect(home_link).to_be_visible()
    expect(about_link).to_be_visible()

    # Verify the admin link in the footer
    admin_link = page.locator('footer').get_by_role('link', name='Admin')
    expect(admin_link).to_be_visible()
    expect(admin_link).to_have_attribute('href', '../admin/dashboard.php')

    # 4. Take a screenshot
    page.screenshot(path="jules-scratch/verification/navigation-and-admin-link.png")

    browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)