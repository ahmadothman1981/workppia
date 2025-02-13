<?php loadPartial('head'); ?>
    <!-- Nav -->
<?php loadPartial('navbar'); ?>
    <!-- Top Banner -->


<section class="bg-blue-900 text-white py-6 text-center">
      <div class="container mx-auto">
        <h2 class="text-3xl font-semibold">Unlock Your Career Potential</h2>
        <p class="text-lg mt-2">
          Discover the perfect job opportunity for you.
        </p>
      </div>
    </section>

    <section>
      <div class="container mx-auto p-4 mt-4">
         <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?></div>
         <p class="text-center text-2xl mb-4">
            <?= $message ?>
         </p>
         <a href="/" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">Go Home</a>
      </div>
      </section>
      <?php loadPartial('footer'); ?>