<label for="name">Name: </label>
<input type="text" id="name" name="name" value="<?= $product['name'] ?? ''; ?>"/>
<?php if (isset($errors['name'])): ?>
    <p class="error">* <?= $errors['name']; ?></p>
<?php endif; ?>

<label for="description">Description: </label>
<textarea id="description" name="description">
    <?= $product['description'] ?? ''; ?>
</textarea>

<input type="submit" value="<?= $buttonText ?? 'submit'; ?>" />