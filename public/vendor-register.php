<?php
declare(strict_types=1);

$pageTitle = 'Vendor Register';
$activePage = 'vendor-register';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">For Vendors</span>
    <h1 class="font-display">Join Wedora.</h1>
    <p>Share your business details for review and appear in the Kerala wedding-vendor marketplace.</p>
</section>

<section class="form-page">
    <form class="register-form card" data-vendor-register-form>
        <div class="section-heading">
            <span class="eyebrow">Vendor application</span>
            <h2 class="font-display">Business details</h2>
        </div>
        <div class="form-grid two">
            <label>Business name<input class="input-field" name="business_name" required></label>
            <label>Category
                <select class="input-field" name="category" required>
                    <option value="">Select category</option>
                    <?php foreach (wedora_categories() as $category): ?>
                        <option value="<?= h($category['name']) ?>"><?= h($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>City
                <select class="input-field" name="city" required>
                    <option value="">Select city</option>
                    <?php foreach (wedora_cities() as $city): ?>
                        <option value="<?= h($city) ?>"><?= h($city) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Price range<input class="input-field" name="price_range" placeholder="From Rs. 1.5L"></label>
            <label>Contact name<input class="input-field" name="contact_name" required></label>
            <label>Email<input class="input-field" type="email" name="email" required></label>
            <label>Phone<input class="input-field" name="phone"></label>
        </div>
        <label>Message<textarea class="input-field" name="message" rows="5" placeholder="Tell us about your style, service area, and availability."></textarea></label>
        <button class="btn-primary" type="submit">Submit application <?= icon('arrow-right', 16) ?></button>
        <p class="form-status" data-form-status></p>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
