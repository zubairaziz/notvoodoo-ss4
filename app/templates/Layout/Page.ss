<article>
    <% include PageHeader %>

    <div class="grid-container">
        <section class="page-container">
        <div class="page-container__inner">
            $Content

            <% if Form %>
            <div class="form--styled form--fly">
                $Form
            </div>
            <% end_if %>
        </div>
        </section>
    </div>
</article>
