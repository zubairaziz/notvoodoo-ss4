<header class="$PageHeaderClasses $ExtraClass">
    <% if HeaderBackground.Exists %>
        <% with HeaderBackground %>
        <img srcset="$Me.FocusFill(800, 332).URL 800w,
                    $Me.FocusFill(2050, 850).URL 2050w"
            sizes="100vw"
            src="$Me.FocusFill(2050, 850).URL"
            aria-hidden="true"
            class="page-header__bg-image">
        <% end_with %>
    <% end_if %>

    <div class="grid-container">
        <div class="page-header__inner">
        <div class="page-header__columns <% if HeaderPanelLinks %>has-columns<% end_if %>">
            <div class="page-header__left">
            <% if HeaderBread %>
                $HeaderBread
            <% end_if %>

            <% if EnableBreadcrumbs %>
                $Bread
            <% end_if %>

            <h1 class="page-header__title">
                $TextEmphasize($NicePageHeaderTitle)
            </h1>

            <% if HeaderLeadIn %>
                <div class="page-header__lead-in <% if UseLargeLeadIn %>page-header__lead-in--large<% end_if %>">$HeaderLeadIn</div>
            <% end_if %>

            <% if HasCTAs('PageHeader') %>
                <ul class="page-header__ctas <% if CTAs(PageHeader).Count > 1 %>has-multiple<% end_if %>">
                <% loop CTAs('PageHeader') %>
                    <li class="page-header__nav page-header__nav-action <% if Type = 'Video' %>is-full-width<% end_if %>">
                    <% include CallToAction %>
                    </li>
                <% end_loop %>
                </ul>
            <% end_if %>
            </div>

            <% if HeaderPanelLinks %>
            <div class="page-header__right">
                <ul class="page-header__links">
                <% loop HeaderPanelLinks %>
                    <li><a href="#$Anchor" data-scroll><span>></span>$TextDeemphasize($Title)</a></li>
                <% end_loop %>
                </ul>
            </div>
            <% end_if %>

            <%-- Allow pages to define a custom slot area --%>
            <% if AdditionalHeaderSlotBottom %>
            $AdditionalHeaderSlotBottom
            <% end_if %>
        </div>
        </div>
    </div>
</header>
