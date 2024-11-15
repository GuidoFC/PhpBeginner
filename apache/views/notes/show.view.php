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
                    <input type="hidden" name="id" value="<?= $note['id'] ?>">
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700">
                        Borrar Usando Formulario method="POST"
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