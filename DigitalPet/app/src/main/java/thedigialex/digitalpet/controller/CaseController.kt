package thedigialex.digitalpet.controller

import android.animation.ObjectAnimator
import android.annotation.SuppressLint
import android.content.Context
import android.graphics.Bitmap
import android.graphics.drawable.AnimationDrawable
import android.graphics.drawable.BitmapDrawable
import android.os.Handler
import android.os.Looper
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService
import thedigialex.digitalpet.model.entities.Item
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.entities.UserTrainingEquipment
import thedigialex.digitalpet.util.SpriteManager
import java.sql.Timestamp
import kotlin.random.Random

@SuppressLint("SetTextI18n")
class CaseController(private val caseBackground: ConstraintLayout, private val context: Context, private val fetchService: FetchService, private val user: User, private val screenWidth: Int) {
    private var menuCycle: Int = -1
    private var isAnimating: Boolean = false
    private var miningEffort: Int = -1
    private var trainingEffort: Int = 0
    private var trainingState: Int = 0
    private var oldXValue: Int = 0
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)

    private var menuController: MenuController =
        MenuController(caseBackground.findViewById(R.id.menuLayout))
    private var menuLayout: ViewGroup = caseBackground.findViewById(R.id.menuLayout)

    private val handler = Handler(Looper.getMainLooper())
    private var isHandlerRunning: Boolean = false
    private lateinit var runnable: Runnable

    private lateinit var trainingEquipment: UserTrainingEquipment

    private var animationLayout: ViewGroup = caseBackground.findViewById(R.id.animationLayout)
    private var mainImage: ImageView = caseBackground.findViewById(R.id.mainImageView)
    private var emotionImageView: ImageView = caseBackground.findViewById(R.id.emotionImageView)
    private var menuImages: Array<ImageView> =
        arrayOf(
            caseBackground.findViewById(R.id.topMenu_0),
            caseBackground.findViewById(R.id.topMenu_1),
            caseBackground.findViewById(R.id.topMenu_2),
            caseBackground.findViewById(R.id.topMenu_3),
            caseBackground.findViewById(R.id.bottomMenu_0),
            caseBackground.findViewById(R.id.bottomMenu_1),
            caseBackground.findViewById(R.id.bottomMenu_2),
            caseBackground.findViewById(R.id.bottomMenu_3),
        )
    private var caseButtons: Array<Button> =
        arrayOf(
            caseBackground.findViewById(R.id.upButton),
            caseBackground.findViewById(R.id.bottomButton),
            caseBackground.findViewById(R.id.leftButton),
            caseBackground.findViewById(R.id.rightButton),
            caseBackground.findViewById(R.id.switchButton),
        )
    private var dirtImageIds = listOf(
        R.id.dirtImageOne,
        R.id.dirtImageTwo,
        R.id.dirtImageThree,
        R.id.dirtImageFour
    )

    init {
        setupImageResources()
        setupButtons()
        caseBackground.findViewById<TextView>(R.id.subNameTitleView).text = "Bits: " + user.bits.toString()
        user.mainDigitalMonster = user.findMainDigitalMonster()
        if (user.mainDigitalMonster == null) {
            caseButtons[1].isClickable = false
            var menuLimit = 0
            val allSprites = mutableListOf<Bitmap>()
            val allTitles = mutableListOf<String>()
            user.eggs?.forEach { monster ->
                monster.sprites?.firstOrNull()?.let { firstSprite ->
                    allSprites.add(firstSprite)
                }
                allTitles.add(monster.name)
                menuLimit++
            }
            menuController.openMenu(-10, menuLimit, allSprites, allTitles)
        } else {
            user.mainDigitalMonster!!.digital_monster.animation(mainImage, if (user.mainDigitalMonster!!.energy != 0) 1 else 4)
            mainImage.setOnClickListener{ pet() }
            updateBackground(user.mainDigitalMonster!!.sleepStartedAt != null)
            checkClean()
        }
    }

    private fun setupImageResources() {
        val defaultMenuImages = listOf(
            R.drawable.stat_menu, R.drawable.food_menu, R.drawable.train_menu,
            R.drawable.clean_menu, R.drawable.light_menu, R.drawable.battle_menu,
            R.drawable.game_menu, R.drawable.shop_menu
        )
        val highlightedMenuImages = listOf(
            R.drawable.stat_menu_highlight,
            R.drawable.food_menu_highlight,
            R.drawable.train_menu_highlight,
            R.drawable.clean_menu_highlight,
            R.drawable.light_menu_highlight,
            R.drawable.battle_menu_highlight,
            R.drawable.game_menu_highlight,
            R.drawable.shop_menu_highlight
        )
        for (i in defaultMenuImages.indices) {
            emptyMenuImageResources[i] =
                if (!menuController.isSettings) defaultMenuImages[i] else R.drawable.stat_menu
            filledMenuImageResources[i] =
                if (!menuController.isSettings) highlightedMenuImages[i] else R.drawable.stat_menu_highlight
        }
        updateMenuImages()
    }

    private fun setupButtons() {
        val actions = listOf(
            { accept() }, { cancel() }, { select(-1) }, { select(1) }, { switchMenu() }
        )
        caseButtons.forEachIndexed { index, button ->
            button.setOnClickListener { actions[index]() }
        }
    }

    private fun updateMenuImages() {
        menuImages.forEachIndexed { i, imageView ->
            imageView.setBackgroundResource(if (i == menuCycle) filledMenuImageResources[i] else emptyMenuImageResources[i])
        }
    }

    private fun selectEgg(name: String) {
        if (name.isEmpty() || name.length > 12) {
            menuController.displayMessage("name error")
        } else {
            val selectedDigitalMonsterId = user.eggs?.get(menuController.menuCycle)?.id ?: return
            fetchService.createNewUserDigitalMonster(
                selectedDigitalMonsterId,
                name
            ) { newUserDigitalMonster ->
                newUserDigitalMonster?.let { newMonster ->
                    CoroutineScope(Dispatchers.Main).launch {
                        user.mainDigitalMonster = newMonster
                        user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
                        mainImage.setOnClickListener{ pet() }
                        caseButtons[1].isClickable = true
                        cancel()
                    }
                }
            }
        }
    }

    private fun performAction(actionType: String) {
        if(actionCheck(actionType)) {
            when (actionType) {
                "Lighting" -> switchLight()
                "Consumable" -> {
                    if (user.mainDigitalMonster!!.hunger < 4) {
                        startAnimation(2, 4500L, actionType)
                    } else {
                        menuController.displayMessage("Not Hungry")
                    }
                }
                "Cleaning" -> {
                    startAnimation(2, 4500L, actionType)
                    user.mainDigitalMonster!!.clean = 0
                    for (i in 0 until 4) {
                        val imageView = caseBackground.findViewById<ImageView>(dirtImageIds[i])
                        stopDirt(imageView)
                    }
                }
                else -> if(checkEnergy()) {
                    startAnimation(3, 9000L, actionType)
                }
            }
        }
    }

    private fun actionCheck(actionType: String): Boolean {
        if (isHandlerRunning) {
            stopAnimation(false)
            return false
        }
        else {
            if ((menuController.menuImageResources.isNotEmpty() ||
                        menuController.subMenuImageResources.isNotEmpty()) &&
                user.mainDigitalMonster?.digital_monster?.stage != "Egg" &&
                (actionType == "Lighting" || user.mainDigitalMonster?.sleepStartedAt == null)) {
                return true
            } else {
                menuController.displayMessage("Unable to perform action.")
                return false
            }
        }
    }

    private fun checkEnergy(): Boolean {
        if (user.mainDigitalMonster?.energy!! > 0) {
            if(user.mainDigitalMonster!!.clean < 4) {
                user.mainDigitalMonster!!.energy -= 1
                return true
            }
            else {
                menuController.displayMessage("Too Dirty")
                return false
            }
        } else {
            menuController.displayMessage("No Energy")
            return false
        }
    }

    private fun openShopMenu() {
        var itemType = "Consumable"
        when (menuController.menuCycle) {
            0 -> itemType = "Case"
            1 -> itemType = "Attack"
            2 -> itemType = "Background"
        }
        fetchService.getItemsForSale(user, itemType, context) {
            if (user.itemsForSale != null) {
                val allSprites = mutableListOf<Bitmap>()
                val allTitles = mutableListOf<String>()
                user.itemsForSale!!.forEach { item ->
                    item.sprites?.firstOrNull()?.let { firstSprite ->
                        allSprites.add(firstSprite)
                    }
                    allTitles.add("Price: " + item.price)
                }
                menuController.openMenu(8, user.itemsForSale!!.size, allSprites, allTitles)
            } else {
                CoroutineScope(Dispatchers.Main).launch {
                    menuController.displayMessage("No Items")
                }
            }
        }
    }

    private fun buyItem() {
        val item: Item = user.itemsForSale!![menuController.menuCycle]
        if(user.bits > item.price) {
            fetchService.buyItem(user, item.id, context) {
                user.bits -= item.price
                caseBackground.findViewById<TextView>(R.id.subNameTitleView).text = "Bits: " + user.bits.toString()
                CoroutineScope(Dispatchers.Main).launch {
                    menuController.displayMessage("Item Bought")
                }
                if(item.type != "Consumable"){
                    user.itemsForSale = user.itemsForSale?.filter { it != item }?.toMutableList()
                    this.cancel()
                }
            }
        }
        else{
            menuController.displayMessage("not enough bits")
        }
    }

    private fun switchLight() {
        if (user.mainDigitalMonster!!.sleepStartedAt == null) {
            updateBackground(true)
            user.mainDigitalMonster!!.sleepStartedAt =
                Timestamp(System.currentTimeMillis() / 1000 * 1000).toString()
        } else {
            updateBackground(false)
            val timeDifferenceMinutes = ((System.currentTimeMillis() / 1000 * 1000) - (Timestamp.valueOf(user.mainDigitalMonster!!.sleepStartedAt).time)) / 60000
            var baseEnergyGainRate = 1 + user.getEquipmentByType("Lighting").firstOrNull()!!.level
            when (user.mainDigitalMonster!!.digital_monster.stage) {
                "Fresh" -> baseEnergyGainRate += 3
                "Child" -> baseEnergyGainRate += 2
                "Rookie" -> baseEnergyGainRate += 1
            }
            if(baseEnergyGainRate * timeDifferenceMinutes > user.mainDigitalMonster!!.maxEnergy) {
                user.mainDigitalMonster!!.energy = user.mainDigitalMonster!!.maxEnergy
            }
            else {
                user.mainDigitalMonster!!.energy = (baseEnergyGainRate * timeDifferenceMinutes).toInt()
            }
            user.mainDigitalMonster!!.sleepStartedAt = null
        }
        cancel()
    }

    private fun updateBackground(isAsleep: Boolean) {
        val background: ConstraintLayout = caseBackground.findViewById(R.id.mainView)
        if (isAsleep) {
            background.setBackgroundResource(R.color.secondary)
            mainImage.visibility = View.INVISIBLE
            emotionImageView.visibility = View.VISIBLE
            emotionImageView.setBackgroundResource(R.drawable.sleepingemotion)
            val animationDrawable = emotionImageView.background as AnimationDrawable
            animationDrawable.start()
            stopWalkingAnimation()
        } else {
            val sprites = user.getEquippedItem("Background")?.sprites
            if (!sprites.isNullOrEmpty()) {
                val bitmapDrawable = BitmapDrawable(context.resources, sprites[0])
                background.background = bitmapDrawable
            }
            emotionImageView.visibility = View.INVISIBLE
            val emotionImage = emotionImageView.background
            if (emotionImage is AnimationDrawable) {
                emotionImage.stop()
            }
            mainImage.visibility = View.VISIBLE
            if(user.mainDigitalMonster!!.energy > 0) {
                startWalkingAnimation()
            }
            checkEvoPoints(false)
        }
    }

    private fun startWalkingAnimation() {
        if (isAnimating) return
        isAnimating = true
        fun startRandomMovement() {
            if (!isAnimating) return
            val randomX = Random.nextInt(-screenWidth + ( mainImage.width / 2), screenWidth - ( mainImage.width / 2)) / 4
            mainImage.scaleX = if (randomX < oldXValue) 1f else -1f
            oldXValue = randomX
            ObjectAnimator.ofFloat(mainImage, "translationX", randomX.toFloat()).apply {
                duration = 1500
                start()
            }
            val randomDelay = Random.nextLong(1500, 6000)
            handler.postDelayed({ startRandomMovement() }, randomDelay)
        }
        startRandomMovement()
    }

    private fun stopWalkingAnimation() {
        isAnimating = false
        handler.removeCallbacksAndMessages(null)
    }

    private fun select(direction: Int) {
        if (menuController.isMenuOpen)
            menuController.cycleMenu(direction)
        else {
            menuCycle = (menuCycle + direction + 8) % 8
            updateMenuImages()
        }
    }

    private fun cancel() {
        if (menuController.isMenuOpen) {
            menuController.reset()
            animationLayout.visibility = View.GONE
            if (user.mainDigitalMonster!!.sleepStartedAt == null) {
                mainImage.visibility = View.VISIBLE
            }
            else {
                emotionImageView.visibility = View.VISIBLE
            }
            if (isHandlerRunning) {
                stopAnimation(true)
            }
        } else {
            menuCycle = -1
            updateMenuImages()
        }
    }

    private fun accept() {
        if (!menuController.isMenuOpen) {
            val allSprites = mutableListOf<Bitmap>()
            val allTitles = mutableListOf<String>()
            var maxCycle = 0
            when (menuCycle) {
                0 -> {
                    val stats: Array<String> = Array(12) { "0" }
                    stats[0] = user.mainDigitalMonster?.level.toString()
                    stats[1] = user.mainDigitalMonster?.digital_monster?.stage.toString()
                    stats[2] =
                        "${user.mainDigitalMonster?.trainings} / ${user.mainDigitalMonster?.maxTrainings}"
                    stats[3] =
                        "${user.mainDigitalMonster!!.wins} / ${(user.mainDigitalMonster!!.losses) + (user.mainDigitalMonster!!.wins)}"
                    stats[4] = user.mainDigitalMonster?.strength.toString()
                    stats[5] = user.mainDigitalMonster?.defense.toString()
                    stats[6] = user.mainDigitalMonster?.agility.toString()
                    stats[7] = user.mainDigitalMonster?.mind.toString()
                    stats[8] = user.mainDigitalMonster?.hunger.toString()
                    stats[9] = user.mainDigitalMonster?.exercise.toString()
                    stats[10] =
                        "${((user.mainDigitalMonster!!.energy.toDouble() / user.mainDigitalMonster!!.maxEnergy ) * 4).toInt()}"
                    stats[11] =
                        "${((user.mainDigitalMonster!!.currentEvoPoints.toDouble() /  user.mainDigitalMonster!!.digital_monster.requiredEvoPoints ) * 4).toInt()}"
                    menuController.stats = stats
                    maxCycle = 4
                }
                1 -> {
                    maxCycle = user.getConsumableItems().size
                    user.getConsumableItems().forEach { inventoryItem ->
                        inventoryItem.item.sprites?.firstOrNull()?.let { firstSprite ->
                            allSprites.add(firstSprite)
                        }
                        allTitles.add(inventoryItem.item.name)
                    }
                }
                2 -> {
                    maxCycle = user.getEquipmentByType("Training").size
                    user.getEquipmentByType("Training").forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        allTitles.add(userTrainingEquipment.trainingEquipment.name)
                    }
                }
                3 -> {
                    maxCycle = user.getEquipmentByType("Cleaning").size
                    user.getEquipmentByType(("Cleaning")).forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        allTitles.add(userTrainingEquipment.trainingEquipment.name)
                    }
                }
                4 -> {
                    maxCycle = user.getEquipmentByType("Lighting").size
                    user.getEquipmentByType("Lighting").forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        allTitles.add(userTrainingEquipment.trainingEquipment.name)
                    }
                }
                5 -> {
                    maxCycle = 4
                    menuController.subMenuImageResources = mutableListOf(
                        R.drawable.battle_menu,
                        R.drawable.battle_menu_highlight,
                        R.drawable.battle_menu,
                        R.drawable.battle_menu_highlight
                    )
                }
                6 -> {
                    maxCycle = 1
                    menuController.subMenuImageResources = mutableListOf(
                        R.drawable.miningone
                    )
                    allTitles.add("Mining")
                }
                7 -> {
                    maxCycle = 4
                    menuController.subMenuImageResources = mutableListOf(
                        R.drawable.shop_menu,
                        R.drawable.shop_menu_highlight,
                        R.drawable.battle_menu,
                        R.drawable.battle_menu_highlight
                    )
                    allTitles.add("Backgrounds")
                    allTitles.add("Attacks")
                    allTitles.add("Case")
                    allTitles.add("Consumable")
                }
            }
            if (menuCycle != -1) {
                mainImage.visibility = View.INVISIBLE
                emotionImageView.visibility = View.INVISIBLE
                menuController.openMenu(menuCycle, maxCycle, allSprites, allTitles)
            }
        } else {
            when (menuController.currentOpenMenuId) {
                -10 -> selectEgg(menuController.editText.text.toString())
                1 -> performAction("Consumable")
                2 -> {
                    if(trainingState == 1) {
                        trainingState = 2
                        trainingEquipment.trainingEquipment.stopAnimation()
                        var animationToPlay = 5
                        if(trainingEffort > 70) {
                            animationToPlay = 6
                        }
                        startAnimation(animationToPlay, 5000L, "Result")
                    }
                    if(trainingState == 0) {
                        performAction("Training")
                    }
                }
                3 -> performAction("Cleaning")
                4 -> performAction("Lighting")
                5 -> performAction("Battle")
                6 -> {
                    if(miningEffort == -1){
                        miningEffort = 0
                        performAction("Game")
                    }
                    else { miningEffort = (miningEffort + 5).coerceAtMost(100)
                    }
                }
                7 -> openShopMenu()
                8 -> buyItem()
            }
        }
    }

    private fun stopAnimation(calledFromCancel: Boolean) {
        animationLayout.visibility = View.GONE
        if (!calledFromCancel) {
            cancel()
        }
        if(miningEffort != -1) {
            user.bits += miningEffort / 2
        }
        if (trainingState == 2) {
            user.useTrainingEquipment(user.getEquipmentByType("Training")[menuController.menuCycle], trainingEffort/20)
            checkClean()
        }
        checkEvoPoints(false)
        SpriteManager.stopSideAnimation()
        user.mainDigitalMonster!!.digital_monster.animation(mainImage, if (user.mainDigitalMonster!!.energy != 0) 1 else 4)
        if(user.mainDigitalMonster!!.energy == 0) {
            stopWalkingAnimation()
        }
        isHandlerRunning = false
        miningEffort = -1
        trainingState = 0
        if (::runnable.isInitialized) {
            handler.removeCallbacks(runnable)
        }
    }

    private fun startAnimation(animationToPlay: Int, animationTimer: Long, animationType: String) {
        menuLayout.visibility = View.GONE
        val effortImageView = animationLayout.findViewById<ImageView>(R.id.animationBarImageView)
        val animationImageView = animationLayout.findViewById<ImageView>(R.id.animationObjectImageView)
        effortImageView.visibility = View.INVISIBLE
        animationLayout.visibility = View.VISIBLE
        if (animationType == "Consumable") {
            val usedItem = user.getUsedItem(menuController.menuCycle)
            usedItem.item.animation(animationLayout.findViewById(R.id.animationObjectImageView))
            fetchService.updateInventoryItem(usedItem)
        }
        if(animationType == "Training" || animationType == "Cleaning") {
            trainingEquipment = if (animationType == "Training") {
                trainingState = 1
                trainingEffort = 0
                effortImageView.visibility = View.VISIBLE
                user.getEquipmentByType("Training")[menuController.menuCycle]
            } else {
                user.getEquipmentByType("Cleaning")[menuController.menuCycle]
            }
            trainingEquipment.trainingEquipment.animation(animationLayout.findViewById(R.id.animationObjectImageView))
        }
        user.mainDigitalMonster!!.digital_monster.animation(
            animationLayout.findViewById(R.id.animationUserImageView),
            animationToPlay
        )
        animationImageView.background = null
        animationImageView.setImageBitmap(null)
        isHandlerRunning = true
        if (::runnable.isInitialized) {
            handler.removeCallbacks(runnable)
        }
        val startTime = System.currentTimeMillis()
        runnable = object : Runnable {
            override fun run() {
                val currentTime = System.currentTimeMillis()
                val elapsedTime = currentTime - startTime
                if (elapsedTime < animationTimer - 100L) {
                    if (trainingState == 1) {
                        trainingEffort += 5
                        if (trainingEffort > 100) {
                            trainingEffort = 0
                        }
                        when (trainingEffort) {
                            in 0..19 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_0)
                            in 20..39 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_25)
                            in 40..59 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_50)
                            in 60..79 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_75)
                            in 80..100 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_100)
                        }
                    }
                    if(miningEffort != -1) {
                        when (miningEffort) {
                            in 0..19 -> animationImageView.setBackgroundResource(R.drawable.miningone)
                            in 20..39 -> animationImageView.setBackgroundResource(R.drawable.miningtwo)
                            in 40..59 -> animationImageView.setBackgroundResource(R.drawable.miningthree)
                            in 60..79 -> animationImageView.setBackgroundResource(R.drawable.miningfour)
                            in 80..100 -> animationImageView.setBackgroundResource(R.drawable.miningfive)
                        }
                    }
                    handler.postDelayed(this, 100)
                } else {
                    if(isHandlerRunning) {
                        stopAnimation(false)
                    }
                }
            }
        }
        handler.post(runnable)
    }

    private fun switchMenu() {
        if (!menuController.isMenuOpen) {
            menuController.isSettings = !menuController.isSettings
            setupImageResources()
        }
    }

    private fun pet() {
        if (user.mainDigitalMonster!!.currentEvoPoints >= user.mainDigitalMonster!!.digital_monster.requiredEvoPoints) {
            fetchService.saveUserDigitalMonster(user.mainDigitalMonster!!) { success ->
                if (success) {
                    fetchService.evolveUserDigitalMonster { evolvedMonster ->
                        evolvedMonster?.let { monster ->
                            CoroutineScope(Dispatchers.Main).launch {
                                user.mainDigitalMonster = monster
                                user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
                                checkEvoPoints(true)
                            }
                        }
                    }
                }
            }
        }
        else {
            if(user.mainDigitalMonster?.digital_monster?.stage == "Egg"){
                user.mainDigitalMonster!!.apply {
                    currentEvoPoints = (currentEvoPoints + 5).coerceAtMost(digital_monster.requiredEvoPoints)
                    fetchService.saveUserDigitalMonster(user.mainDigitalMonster!!)
                }
            }
        }
    }

    private fun checkClean() {
        for (i in 0 until user.mainDigitalMonster!!.clean) {
            val imageView = caseBackground.findViewById<ImageView>(dirtImageIds[i])
            playDirt(imageView)
        }
    }

    private fun playDirt(imageView: ImageView) {
        imageView.visibility = View.VISIBLE
        imageView.setBackgroundResource(R.drawable.dirtanimation)
        val animationDrawable = imageView.background as AnimationDrawable
        animationDrawable.start()
    }

    private fun stopDirt(imageView: ImageView) {
        val background = imageView.background
        if (background is AnimationDrawable) {
            background.stop()
        }
        imageView.visibility = View.INVISIBLE
    }

    private fun checkEvoPoints(stop: Boolean) {
        if(user.mainDigitalMonster!!.currentEvoPoints >= user.mainDigitalMonster!!.digital_monster.requiredEvoPoints && !stop) {
            emotionImageView.visibility = View.VISIBLE
            emotionImageView.setBackgroundResource(R.drawable.happyemotion)
            val animationDrawable = emotionImageView.background as AnimationDrawable
            animationDrawable.start()
        }
        if(stop) {
            emotionImageView.visibility = View.INVISIBLE
            val emotionImage = emotionImageView.background
            if (emotionImage is AnimationDrawable) {
                emotionImage.stop()
            }
        }
        caseBackground.findViewById<TextView>(R.id.subNameTitleView).text = "Bits: " + user.bits.toString()
    }
}
