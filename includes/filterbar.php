<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">
                <h2>Blogs</h2>
            </a>
            <form method="post">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" placeholder="Enter Heading"
                    value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
                <label for="category">Category filter</label>
                <select name="category" id="category">
                    <option value="">Select Category</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php $selected = ($category['id'] == $selectedCategory) ? 'selected' : ''; ?>
                            <option value="<?= $category['id'] ?>" <?= $selected ?>>
                                <?= $category['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No category available</option>
                    <?php endif; ?>
                </select>

                <label for="subcategory">Sub-Categories filter</label>
                <select name="subcategory" id="subcategory">
                    <option value="">Select Sub-Category</option>
                </select>
                <button class="btn btn-success" type="submit" name="filter">Apply</button>
            </form>

        </div>

    </nav>