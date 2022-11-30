<div id="colours_container" class="colours_container">

    <?php foreach($productColors as $key=>$imageSrc) : ?>
        <div class="colour_container">
            <button class="colour_image_container <?php echo ($key == 0) ? 'selected' : '' ?>">
                <img class="arrow_image" src= <?= $imageSrc ?>>
            </button>

            <div class="colour_name_container">
                <div class="colour" style="background-color: red"></div>

                <h3 class="colour_name">Rouge</h3>
            </div>
        </div>
    <?php endforeach; ?>
</div>