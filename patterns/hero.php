<?php
/**
 * Title: Editor-friendly hero
 * Slug: my-pattern-library/hero
 * Categories: my-pattern-library, hero
 * Description: A hero section with editable heading, copy, and link.
 * Keywords: hero, banner, call to action
 */
?>
<!-- wp:cover {"url":"hero.jpg","dimRatio":0,"tagName":"section","align":"full","style":{"color":{"background":"#0f172a"}}} -->
<section class="wp-block-cover alignfull has-background" style="background-color:#0f172a"><img class="wp-block-cover__image-background" alt="" src="hero.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:heading {"level":1,"style":{"color":{"text":"#ffffff"}}} -->
<h1 class="wp-block-heading has-text-color" style="color:#ffffff">Design that editors can change.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"color":{"text":"#cbd5e1"}}} -->
<p class="has-text-color" style="color:#cbd5e1">No frozen Custom HTML.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"color":{"background":"#f97316","text":"#ffffff"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="#" style="color:#ffffff;background-color:#f97316">Learn more</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></section>
<!-- /wp:cover -->