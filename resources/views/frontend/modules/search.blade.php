<div class="Tile Search">
    <div class="Tile__heading Search__heading">
        Search
    </div>
    <div class="Tile__body Search__body">
        <form action="">
            <div class="form-group">
                <label for="">City</label>
                <input type="text"
                       class="JS--Search__autoCompleteDutchCites form-control"
                       name="city"
                >
            </div>
            <div class="form-group">
                <label for="">Age</label>
                <select name="age" class="form-control">
                    <option value="18-25">18-25</option>
                    <option value="25-30">25-30</option>
                </select>
            </div>
            <div class="text-right">
                @include('frontend.components.button', [
                    'buttonContext' => 'form',
                    'buttonType' => 'submit',
                    'buttonState' => 'primary',
                    'buttonText' => 'SEARCH'
                ])
            </div>
        </form>
    </div>
</div>