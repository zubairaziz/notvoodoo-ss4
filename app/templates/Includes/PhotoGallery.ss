<section class="photo-gallery" data-photo-gallery>
  <% loop Photos.Limit(6) %>
    <a href="$Me.AbsoluteURL" class="photo-gallery__photo" data-aos="zoom-in" data-aos-delay="{$Pos}00">
      $Me.FocusFill(520, 375)
    </a>
  <% end_loop %>

  <div class="photo-gallery__block" data-aos="zoom-in" data-aos-delay="{$Photos.Limit(6).Count}99">
    <div class="photo-gallery__block-inner">
      <h3 class="photo-gallery__title">Gallery</h3>
      <button type="button" class="photo-gallery__trigger btn btn--light">$Photos.Count <% if Photos.Count == '1' %>Photo<% else %>Photos<% end_if %></button>
    </div>
  </div>
</section>
