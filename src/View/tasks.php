<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>ToDo список</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-slate-100 to-slate-200 min-h-screen flex flex-col items-center py-10 px-4">

  <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-6">
    <h1 class="text-4xl font-bold text-center mb-8 text-blue-700">📝 Мои задачи</h1>

    <form method="post" action="" class="flex flex-col sm:flex-row gap-4 mb-6">
      <input
        type="text"
        name="description"
        placeholder="Что нужно сделать?"
        required
        class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
      >
      <button
        type="submit"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition"
      >
        ➕ Добавить
      </button>
    </form>

    <div class="flex justify-center gap-4 mb-6">
      <a href="?" class="text-gray-700 hover:text-blue-600 font-medium">Все</a>
      <a href="?filter=done" class="text-gray-700 hover:text-green-600 font-medium">Выполненные</a>
      <a href="?filter=pending" class="text-gray-700 hover:text-red-600 font-medium">Невыполненные</a>
    </div>

    <?php if (empty($tasks)): ?>
      <p class="text-center text-gray-500">Задач пока нет 🙃</p>
    <?php else: ?>
      <ul class="space-y-4">
        <?php foreach ($tasks as $task): ?>
          <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm">
            <span class="<?= $task['status'] === 'ready' ? 'line-through text-gray-400' : 'text-gray-800' ?>">
              <?= htmlspecialchars($task['title']) ?>
            </span>
            <div class="flex items-center gap-3">
              <a href="?action=toggle&id=<?= $task['id'] ?>"
                 class="text-green-600 hover:text-green-800 text-lg"
                 title="Отметить выполненной">
                ✅
              </a>
              <a href="?action=delete&id=<?= $task['id'] ?>"
                 class="text-red-500 hover:text-red-700 text-lg"
                 title="Удалить">
                🗑️
              </a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

</body>
</html>
