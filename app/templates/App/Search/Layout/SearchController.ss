<article class="site-search-results">
  <% include PageHeader %>

  <div class="grid-container">
    <section class="site-search-results__wrapper">
      <div class="site-search-results__title">Results for <strong>'{$SearchTerm}'</strong></div>

      <% if SearchResults.Matches.Count %>
        <ul class="site-search-results-list">
          <% loop SearchResults.Matches %>
            <li class="site-search-results-item <% if Abstract %>has-abstract<% end_if %>">
              <div class="site-search-results-item__inner">
                <a href="$Link" class="site-search-results-item__title ignore-ft">$Title</a>

                <% if Abstract %>
                  <p>$Abstract.XML</p>
                <% end_if %>
              </div>

              <a href="$Link" class="site-search-results-item__more ignore-ft">$AssetIcon('arrow')</a>
            </li>
          <% end_loop %>
        </ul>
      <% else %>
        <div class="search-empty-results">
          No results found
        </div>
      <% end_if %>
    </section>
  </div>
</article>
