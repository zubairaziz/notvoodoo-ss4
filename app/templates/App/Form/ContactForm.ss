<form $AttributesHTML autocomplete="off" novalidate>
  <fieldset>
    <div class="grid-x grid-margin-x">
      <div class="cell large-6">
        $Fields.fieldByName(FirstName).FieldHolder
      </div>

      <div class="cell large-6">
        $Fields.fieldByName(LastName).FieldHolder
      </div>

      <div class="cell large-6">
        $Fields.fieldByName(Email).FieldHolder
      </div>

      <div class="cell large-6">
        $Fields.fieldByName(Phone).FieldHolder
      </div>

      <div class="cell">
        <div class="grid-x grid-margin-x">
          <div class="cell large-6">
            $Fields.fieldByName(City).FieldHolder
          </div>

          <div class="cell large-6">
            <div class="grid-x grid-margin-x">
              <div class="cell large-6">
                $Fields.fieldByName(State).FieldHolder
              </div>

              <div class="cell large-6">
                $Fields.fieldByName(Zip).FieldHolder
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="cell">
      <div class="grid-x grid-margin-x">
        <div class="cell large-6">
          $Fields.fieldByName(HearAboutUs).FieldHolder
        </div>

        <div class="cell large-6">
          $Fields.fieldByName(ContactingUs).FieldHolder
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="cell">
      $Fields.fieldByName(Comments).FieldHolder
    </div>
  </fieldset>

  <div class="form-error-message"></div>

  <% if $Actions %>
    <% loop $Actions %>
      $Field
    <% end_loop %>
  <% end_if %>

  <% loop HiddenFields %>
    $FieldHolder
  <% end_loop %>
</form>
