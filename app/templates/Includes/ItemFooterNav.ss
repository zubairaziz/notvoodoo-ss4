<div class="grid-container">
  <footer class="item-footer-nav">
    <div class="item-footer-nav__prev">
      <% if ItemFooterNav.Prev %>
        <a href="$ItemFooterNav.Prev.Link">$AssetIcon('arrow')<span>Previous</span></a>
      <% end_if %>
    </div>

    <div class="item-footer-nav__return">
      <a href="$HolderPage.Link"><span class="item-footer-nav__return-icon">$AssetIcon('arrow-return')</span> $ReturnLabel</a>
    </div>

    <div class="item-footer-nav__next">
      <% if ItemFooterNav.Next %>
        <a href="$ItemFooterNav.Next.Link">$AssetIcon('arrow')<span>Next</span></a>
      <% end_if %>
    </div>
  </footer>
</div>
