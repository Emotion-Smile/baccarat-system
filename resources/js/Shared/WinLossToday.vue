<template>
  <div class="grid h-full overflow-hidden lg:flex-1">
    <div class="h-full bg-white">
      <div class="py-2  text-center text-white uppercase bg-black">
        Win/Loss Today
      </div>
      <div class="py-3 text-2xl font-medium items-center text-center uppercase">
        {{ todayWinLoss }}
      </div>
    </div>
  </div>
</template>


<script>
export default {
    name: 'WinLossToday',
    props: {
        todayWinLoss: String
    }
};
</script>

<style scoped>

</style>
