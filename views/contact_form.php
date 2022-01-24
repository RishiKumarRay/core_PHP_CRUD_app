<div class="row">
    <div class="col-6 mt-5 mx-auto">
        <form method="post" action="/contact-forms" class="border p-3">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="sender" placeholder="you@domain.com">
                <label for="sender">you@domain.com</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="subject" placeholder="subject">
                <label for="subject">subject</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Message" id="message" name="message" style="height: 120px"></textarea>
                <label for="message">Message</label>
            </div>
            <div class="mb-3">
                <hr>
                <input type="submit" class="btn btn-outline-dark w-100" value="Contact Us">
            </div>
        </form>
    </div>
</div>