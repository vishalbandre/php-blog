<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Term namespace to handle the translations
use Admin\Term;

// Use Translation namespace to handle the translations
use Admin\Translation;

// Use Language namespace to handle the languages
use Admin\Language;

if (!$_SESSION['logged_in'] || !$_SESSION['is_admin']) :
    header('Location: /index.php');
endif;

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /admin/multilingual/translations/index.php');
} else {
    $id = $_GET['id'];
}

$term = new Term();
$term = $term->get($id);
$term = $term->fetch_assoc();
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar-admin.php") ?>
        </div>
        <div class="col-md-9">
            <div class="content-area">
                <h6 class="display-6 mt-3">UI/String Translations</h6>
                <div class="message-board"></div>
                <section>
                    <?php
                    $translation = new Translation();
                    $results = $translation->getTranslationsByTerm($term['term']);

                    if ($results) {
                        $translation_data = $results->fetch_assoc();
                    ?>
                        <div class="mt-2">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a class="btn btn-primary" aria-current="page" href="/admin/multilingual/translations/">Back To All Terms</a>

                                    <h5 class="mt-3 mb-2">Translations for: <?php echo $term['term']; ?></h5>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Translation</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Language</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Operations</strong>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <?php
                            foreach ($results as $result) {
                                $language = new Language();
                            ?>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <input type="hidden" value="<?php echo $result['id']; ?>" class="translation_id">
                                        <input type="hidden" value="<?php echo $result['term_id']; ?>" class="term_id">
                                        <div class="p-2 translation">
                                            <?php
                                            if (!empty($result['translation'])) {
                                                echo $result['translation'];
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 p-2">
                                        <input class="language_id" type="hidden" value="<?php echo $translation_data['language_id']; ?>" class="language_id">
                                        <?php
                                        $lang = $language->getById($result["language_id"]);
                                        $lang_results = $lang->fetch_assoc();
                                        echo $lang_results['name'];
                                        ?>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="edit-translation btn btn-primary">Edit</button>
                                        <button class="cancel-edit btn btn-primary">Cancel</button>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

<script>
    jQuery(document).ready(function($) {
        // Hide .cancel-edit button using jquery
        $('.cancel-edit').hide();

        // Get initial value of .translation in global variable
        var translation_text = $(this).parent().parent().children().find('.translation').text();

        // trim contenteditable in jquery
        translation_text = translation_text.trim();

        $('body').on('click', '.edit-translation', function() {

            // make child divs editable on button click
            $(this).parent().parent().children().find('.translation').attr('contenteditable', 'true').focus();

            // make .cancel-edit button visible
            $(this).parent().parent().children().find('.cancel-edit').show();

            var translation = $(this).parent().parent().children().find('.translation');

            // trim contenteditable in jquery
            translation_text = $(this).parent().parent().children().find('.translation').text();
            translation_text = translation_text.trim();

            // if translation is equal to - set it to empty string
            if (translation_text == '-') {
                translation.text('');
            }

            // on click make other buttons with same class disable jquery
            $('.edit-translation').not(this).prop('disabled', true);

            // on click replace value with new button value with jquery
            $(this).text('Save');

            // on click add class ".currently-editing' and remove ".edit-translation" to this with jquery
            $(this).addClass('currently-editing').removeClass('edit-translation');
        });

        $('body').on("click", '.currently-editing', function() {
            var translation = $(this).parent().parent().children().find('.translation').text();

            // trim contenteditable in jquery
            translation = translation.trim();

            if (translation == '') {
                // Error panel
                // console.log('No empty translations are allowed.')
                // return false;
            }
            $(this).parent().parent().children().find('.translation').attr('contenteditable', 'false');

            // make .cancel-edit button invisible
            $(this).parent().parent().children().find('.cancel-edit').hide();

            // make an ajax request to php api
            $.ajax({ // ajax request to update translation
                url: '/admin/multilingual/translations/api/update.php',
                type: 'POST',
                data: {
                    id: $(this).parent().parent().children().find('.translation_id').val(),
                    term_id: $(this).parent().parent().children().find('.term_id').val(),
                    language_id: $(this).parent().parent().children().find('.language_id').val(),
                    translation: $(this).parent().parent().children().find('.translation').text()
                },
                success: function(response) {
                    if(response.success) {
                        // Success panel
                        // create new div with class message
                        // append message to div
                        // append div to message-board div
                        var message = $('<div class="alert alert-success message">Translation Saved.</div>');
                        $('.message-board').append(message);
                        $('.message').delay(5000).fadeOut('slow');
                        breadcrumb = $(this).parent().parent().children().find('.translation').text()
                    } else {
                        // Error panel
                        var message = $('<div class="alert alert-danger message">Translation Saved.</div>');
                        $('.message-board').append(message);
                        $('.message').delay(5000).fadeOut('slow');
                    }
                },
            });


            // on click make other buttons with same class disable jquery
            $('.edit-translation').not(this).prop('disabled', false);

            // on click replace value with new button value jquery
            $(this).text('Edit');

            // on click add class ".edit-translation" and remove ".currently-editing' to this with jquery
            $(this).addClass('edit-translation').removeClass('currently-editing');
        });

        $('body').on("click", '.cancel-edit', function() {
            $(this).parent().parent().children().find('.translation').attr('contenteditable', 'false');

            // make .cancel-edit button invisible
            $(this).parent().parent().children().find('.cancel-edit').hide();

            // on click make other buttons with same class disable jquery
            $('.edit-translation').not(this).prop('disabled', false);

            // on click add class ".edit-translation" and remove ".currently-editing' to this with jquery
            $(this).parent().parent().children().find('.currently-editing').addClass('edit-translation').removeClass('currently-editing');

            // on click replace value with new button value jquery
            $(this).parent().parent().children().find('.edit-translation').text('Edit');

            var translation = $(this).parent().parent().children().find('.translation');

            // trim contenteditable in jquery
            translation_text = $(this).parent().parent().children().find('.translation').text();
            translation_text = translation_text.trim();

            // if translation is equal to - set it to empty string
            if (translation_text == '') {
                translation.text('-');
            }
        });
    });

    // how to make div editable on click and make ajax request on clicking outside or enter using jquery
</script>

</body>

</html>