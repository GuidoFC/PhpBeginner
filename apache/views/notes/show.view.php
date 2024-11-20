<?php require base_path('views/partials/head.php'); ?>
<?php require base_path('views/partials/nav.php'); ?>
<?php require base_path('views/partials/banner.php'); ?>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p class="mb-6">
                <a href="/notes" class="text-blue-500 underline"> Go back</a>
            </p>
            <p><?= htmlspecialchars($note['body']) ?></p>
            <footer class="mt-6">
                <a href="/note/edit?id= <?= $note['id'] ?>"
                   class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    Edit</a>
                <form action="/note/borrarNota" method="POST" class="inline">
<!--                    TODO inputs q se enviaran cuando le de al boton Borrar,
                            En uno enviare el id de la nota y el otro le dire que quiero enviar un metodo DELETE
                            mirar Index.php-->
                    <input type="hidden" name="id"  value="<?= $note['id'] ?>">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700">
                        Borrar input type="hidden" name="_method" value="DELETE"

                    </button>
                </form>

            </footer>
            <!--            <form class="mt-6" method="POST">-->
            <!--                <input type="hidden" name="_method" value="DELETE">-->
            <!--                <input type="hidden" name="id" value="--><?php //= $note['id'] ?><!--">-->
            <!--                <button class="text-sm text-red-500">Delete</button> </form>-->
        </div>
    </main>
<?php require base_path('views/partials/footer.php') ?>