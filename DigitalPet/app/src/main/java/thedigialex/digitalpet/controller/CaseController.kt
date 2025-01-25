package thedigialex.digitalpet.controller

import android.content.Context
import android.graphics.Bitmap
import android.graphics.drawable.BitmapDrawable
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import androidx.constraintlayout.widget.ConstraintLayout
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService
import thedigialex.digitalpet.model.entities.Item
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.util.SpriteManager
import java.sql.Timestamp

class CaseController(private val caseBackground: ConstraintLayout, private val context: Context, private val fetchService: FetchService, private val user: User) {
    private var menuCycle: Int = -1
    private var isHandlerRunning: Boolean = false
    private var trainingEffort: Int = 0
    private var isTraining: Boolean = false
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)

    private var menuController: MenuController =
        MenuController(caseBackground.findViewById(R.id.menuLayout))

    //menu elements
    private var menuLayout: ViewGroup = caseBackground.findViewById(R.id.menuLayout)

    private val handler = Handler(Looper.getMainLooper())
    private lateinit var runnable: Runnable

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

    init {
        setupImageResources()
        setupButtons()
        user.mainDigitalMonster = user.findMainDigitalMonster()
        if (user.mainDigitalMonster == null) {
            caseButtons[1].isClickable = false
            var menuLimit = 0
            val allSprites = mutableListOf<Bitmap>()
            user.eggs?.forEach { monster ->
                monster.sprites?.firstOrNull()?.let { firstSprite ->
                    allSprites.add(firstSprite)
                }
                menuLimit++
            }
            menuController.openMenu(-10, menuLimit, allSprites)
        } else {
            user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
            mainImage.setOnClickListener{ pet() }
            updateBackground(false)
            if(user.mainDigitalMonster!!.currentEvoPoints >= user.mainDigitalMonster!!.digital_monster.requiredEvoPoints) {
                emotionImageView.setBackgroundResource(R.color.success)
            }
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
        if (menuController.menuImageResources.isNotEmpty() || menuController.subMenuImageResources.isNotEmpty()) {
            if (user.mainDigitalMonster?.digital_monster?.stage != "Egg") {
                if (actionType == "lighting") {
                    //turn light off or on
                } else {
                    if (actionType != "game") {
                        if (isHandlerRunning) {
                            stopAnimation(false)
                        }
                        val (animationDuration, animationStep) = when (actionType) {
                            "consumable", "cleaning" -> 5000L to 2
                            "battle" -> 10000L to 4
                            else -> 10000L to 3
                        }
                        if (actionType == "consumable" || actionType == "cleaning") {
                            startAnimation(animationStep, animationDuration, actionType)
                        }
                        if (user.mainDigitalMonster?.energy!! > 0) {
                            user.mainDigitalMonster!!.energy -= 1
                            startAnimation(animationStep, animationDuration, actionType)
                        } else {
                            menuController.displayMessage("No Energy")
                        }
                    } else {
                        //game
                    }
                }
            } else {
                menuController.displayMessage("Unable to preform action.")
            }
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
            Log.d("test", user.itemsForSale.toString())
            if (user.itemsForSale != null) {
                val allSprites = mutableListOf<Bitmap>()
                user.itemsForSale!!.forEach { item ->
                    item.sprites?.firstOrNull()?.let { firstSprite ->
                        allSprites.add(firstSprite)
                    }
                }
                menuController.openMenu(8, user.itemsForSale!!.size, allSprites)
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
            user.mainDigitalMonster!!.sleepStartedAt = null
        }
        cancel()
    }

    private fun updateBackground(isAsleep: Boolean) {
        val background: ConstraintLayout = caseBackground.findViewById(R.id.mainView)
        if (isAsleep) {
            background.setBackgroundResource(R.color.secondary)
            mainImage.visibility = View.INVISIBLE
        } else {
            val sprites =  user.getEquippedItem("Background")?.sprites
            if (!sprites.isNullOrEmpty()) {
                val bitmapDrawable = BitmapDrawable(context.resources, sprites[0])
                background.background = bitmapDrawable
            }
            mainImage.visibility = View.VISIBLE
        }
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
                        "${(user.mainDigitalMonster!!.energy * 4) / (user.mainDigitalMonster!!.maxEnergy)}"
                    stats[11] =
                        "${(user.mainDigitalMonster!!.currentEvoPoints * 4) / (user.mainDigitalMonster!!.digital_monster.requiredEvoPoints)}"
                    menuController.stats = stats
                    maxCycle = 4
                }

                1 -> {
                    maxCycle = user.getConsumableItems().size
                    user.getConsumableItems().forEach { inventoryItem ->
                        inventoryItem.item.sprites?.firstOrNull()?.let { firstSprite ->
                            allSprites.add(firstSprite)
                        }
                    }
                }

                2 -> {
                    maxCycle = user.getEquipmentByType().size
                    user.getEquipmentByType().forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                    }
                }

                3 -> {
                    maxCycle = user.getEquipmentByType("Cleaning").size
                    user.getEquipmentByType(("Cleaning")).forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                    }
                }

                4 -> {
                    maxCycle = user.getEquipmentByType("Lighting").size
                    user.getEquipmentByType("Lighting").forEach { userTrainingEquipment ->
                        userTrainingEquipment.trainingEquipment.sprites?.firstOrNull()
                            ?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
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
                    maxCycle = 4
                    menuController.subMenuImageResources = mutableListOf(
                        R.drawable.game_menu,
                        R.drawable.game_menu_highlight,
                        R.drawable.battle_menu,
                        R.drawable.battle_menu_highlight
                    )
                }

                7 -> {
                    maxCycle = 4
                    menuController.subMenuImageResources = mutableListOf(
                        R.drawable.shop_menu,
                        R.drawable.shop_menu_highlight,
                        R.drawable.battle_menu,
                        R.drawable.battle_menu_highlight
                    )
                }
            }
            if (menuCycle != -1) {
                menuController.openMenu(menuCycle, maxCycle, allSprites)
            }
        } else {
            when (menuController.currentOpenMenuId) {
                -10 -> selectEgg(menuController.editText.text.toString())
                1 -> performAction("consumable")
                2 -> performAction("training")
                3 -> performAction("cleaning")
                4 -> performAction("lighting")
                5 -> performAction("battle")
                6 -> performAction("game")
                7 -> openShopMenu()
                8 -> buyItem()
            }
        }
    }

    private fun stopAnimation(calledFromCancel: Boolean) {
        if (!calledFromCancel) {
            if (isTraining) {
                user.useTrainingEquipment(user.getEquipmentByType()[menuController.menuCycle])
            }
            cancel()
        }
        SpriteManager.stopSideAnimation()
        user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
        isHandlerRunning = false
        isTraining = false
        if (::runnable.isInitialized) {
            handler.removeCallbacks(runnable)
        }
    }

    private fun startAnimation(animationToPlay: Int, animationTimer: Long, animationType: String) {
        menuLayout.visibility = View.GONE
        animationLayout.visibility = View.VISIBLE
        animationLayout.findViewById<ImageView>(R.id.animationBarImageView).visibility =
            View.INVISIBLE
        if (animationType == "consumable") {
            val usedItem = user.getUsedItem(menuController.menuCycle)
            usedItem.item.animation(animationLayout.findViewById(R.id.animationObjectImageView))
            fetchService.updateInventoryItem(usedItem)
        } else {
            val equipment = if (animationType == "training") {
                isTraining = true
                trainingEffort = 0
                animationLayout.findViewById<ImageView>(R.id.animationBarImageView).visibility =
                    View.VISIBLE
                user.getEquipmentByType()[menuController.menuCycle]
            } else {
                user.getEquipmentByType("Cleaning")[menuController.menuCycle]
            }
            equipment.trainingEquipment.animation(animationLayout.findViewById(R.id.animationObjectImageView))
        }
        user.mainDigitalMonster!!.digital_monster.animation(
            animationLayout.findViewById(R.id.animationUserImageView),
            animationToPlay
        )
        isHandlerRunning = true
        val startTime = System.currentTimeMillis()
        runnable = object : Runnable {
            override fun run() {

                val currentTime = System.currentTimeMillis()
                val elapsedTime = currentTime - startTime
                if (elapsedTime < animationTimer - 100L) {
                    if (isTraining) {
                        trainingEffort += 5
                        if (trainingEffort > 100) {
                            trainingEffort = 0
                        }
                        val effortImageView =
                            animationLayout.findViewById<ImageView>(R.id.animationBarImageView)
                        when (trainingEffort) {
                            in 0..19 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_0)
                            in 20..39 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_25)
                            in 40..59 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_50)
                            in 60..79 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_75)
                            in 80..100 -> effortImageView.setBackgroundResource(R.drawable.energy_bar_100)
                        }
                    }
                    handler.postDelayed(this, 100)
                } else {
                    stopAnimation(false)
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
}
