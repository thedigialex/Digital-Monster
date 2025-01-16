package thedigialex.digitalpet.controller

import android.annotation.SuppressLint
import android.content.Context
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.os.Handler
import android.os.Looper
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.util.SpriteManager
import java.sql.Timestamp

class CaseController(private val caseBackground: ConstraintLayout, private val context: Context, private val fetchService: FetchService, private val user: User) {
    private var menuCycle: Int = -1
    private var innerMenuCycle: Int = 0
    private var menuId: Int = -1
    private var isHandlerRunning: Boolean = false
    private var menuMax: Int = 0
    private var trainingEffort: Int = 0
    private var isMenuOpen: Boolean = false
    private var isSettings = false
    private var isTraining: Boolean = false
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)
    private var imageResources: MutableList<Bitmap> = mutableListOf()

    private var menuController: MenuController = MenuController( caseBackground.findViewById(R.id.menuLayout))

    //menu elements
    private var menuLayout: ViewGroup = caseBackground.findViewById(R.id.menuLayout)
    private var title: TextView = menuLayout.findViewById(R.id.title)
    private var count: TextView = menuLayout.findViewById(R.id.count)
    private var alertText: TextView = menuLayout.findViewById(R.id.alertText)
    private var editText: EditText = menuLayout.findViewById(R.id.editInput)
    private var iconImage: ImageView = menuLayout.findViewById(R.id.iconImage)
    private var statsViewLayout: ConstraintLayout = menuLayout.findViewById(R.id.statsViewLayout)


    private val handler = Handler(Looper.getMainLooper())
    private lateinit var runnable: Runnable

    private var animationLayout: ViewGroup = caseBackground.findViewById(R.id.animationLayout)
    private var mainImage: ImageView = caseBackground.findViewById(R.id.mainImageView)
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
    private var caseButtons: Array<Button>  =
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
        if(user.mainDigitalMonster == null) {
            setUpEggs()
        }
        else{
            user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
        }
    }

    private fun setupImageResources() {
        val defaultMenuImages = listOf(
            R.drawable.stat_menu, R.drawable.food_menu, R.drawable.train_menu,
            R.drawable.clean_menu, R.drawable.light_menu, R.drawable.battle_menu,
            R.drawable.game_menu, R.drawable.shop_menu
        )
        val highlightedMenuImages = listOf(
            R.drawable.stat_menu_highlight, R.drawable.food_menu_highlight, R.drawable.train_menu_highlight,
            R.drawable.clean_menu_highlight, R.drawable.light_menu_highlight, R.drawable.battle_menu_highlight,
            R.drawable.game_menu_highlight, R.drawable.shop_menu_highlight
        )
        for (i in defaultMenuImages.indices) {
            emptyMenuImageResources[i] = if (!isSettings) defaultMenuImages[i] else R.drawable.stat_menu
            filledMenuImageResources[i] = if (!isSettings) highlightedMenuImages[i] else R.drawable.stat_menu_highlight
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

    private fun setUpEggs() {
        caseButtons[1].isClickable = false
        if (user.eggs?.isNotEmpty() == true) {
            val allSprites = mutableListOf<Bitmap>()
            user.eggs?.forEach { monster ->
                monster.sprites?.firstOrNull()?.let { firstSprite ->
                    allSprites.add(firstSprite)
                }
                menuMax++
            }
            imageResources = allSprites
            menuId = -10
            isMenuOpen = true
            updateMenuLayout()
            updateMenuIcon()
        }
    }

    private fun updateMenuImages() {
        menuImages.forEachIndexed { i, imageView ->
            imageView.setBackgroundResource(if (i == menuCycle) filledMenuImageResources[i] else emptyMenuImageResources[i])
        }
    }

    @SuppressLint("SetTextI18n")
    private fun updateMenuLayout() {
        menuLayout.visibility = View.VISIBLE
        mainImage.visibility = View.GONE
        alertText.visibility = View.GONE
        editText.visibility = View.GONE
        iconImage.visibility = View.GONE
        statsViewLayout.visibility = View.GONE
        when (menuId) {
            -10 ->  {
                title.text = context.getString(R.string.select_egg)
                iconImage.visibility = View.VISIBLE
                editText.visibility = View.VISIBLE
            }
            0 -> {
                if (!isSettings) {
                    title.text = context.getString(R.string.stats)
                    menuMax = 4
                    menuLayout.findViewById<ConstraintLayout>(R.id.statsViewLayout).visibility = View.VISIBLE
                    updateStatMenu()
                } else {
                    title.text = "Settings 0"
                }
            }
            1 -> {
                if (!isSettings) {
                    title.text = "Food"
                    val consumableItems = user.getConsumableItems()
                    if (consumableItems.isEmpty()) {
                        displayMessage("No Items")
                        cancel()
                    } else {
                        menuMax = consumableItems.size
                        val allSprites = mutableListOf<Bitmap>()
                        consumableItems.forEach { inventoryItem ->
                            inventoryItem.item.sprites?.firstOrNull()?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        }
                        imageResources = allSprites
                        menuLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
                        updateMenuIcon()
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            2 ->  {
                if (!isSettings) {
                    title.text = "Training"
                    val trainingEquipment = user.getTrainingEquipment()
                    if (trainingEquipment.isEmpty()) {
                        displayMessage("No Training Equipment")
                        cancel()
                    } else {
                        menuMax = trainingEquipment.size
                        val allSprites = mutableListOf<Bitmap>()
                        trainingEquipment.forEach { equipment ->
                            equipment.trainingEquipment.sprites?.firstOrNull()?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        }
                        imageResources = allSprites
                        menuLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
                        updateMenuIcon()
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            3 -> {
                if(!isSettings) {
                    performAction("cleaning")
                }
                else {
                    title.text = "Settings 3"
                }
            }
            4 ->  {
                if(!isSettings) {
                    switchLight()
                }
                else {
                    title.text = "Settings 3"
                }
            }
            5 ->  title.text = if (!isSettings) "Accept 5" else "Settings 5"
            6 ->  title.text = if (!isSettings) "Accept 6" else "Settings 6"
            7 -> {
                if (!isSettings) {
                    title.text = "Shop"
                    menuMax = 4
                    menuLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
                    imageResources = mutableListOf(
                        BitmapFactory.decodeResource(context.resources, R.drawable.food_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.stat_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.train_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.clean_menu_highlight)
                    )
                    updateMenuIcon()
                } else {
                    title.text = "Settings 7"
                }
            }
            8 -> {
                title.text = "item for sale"
                menuMax = user.itemsForSale!!.size
                val allSprites = mutableListOf<Bitmap>()
                user.itemsForSale!!.forEach { item ->
                    item.sprites?.firstOrNull()?.let { firstSprite ->
                        allSprites.add(firstSprite)
                    }
                }
                imageResources = allSprites
                menuLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
                updateMenuIcon()
            }
        }
        count.text = "${innerMenuCycle + 1} / $menuMax"
    }

    @SuppressLint("SetTextI18n")
    private fun updateMenuIcon() {
        val  iconImage = menuLayout.findViewById<ImageView>(R.id.iconImage)
        iconImage.setImageBitmap(imageResources[innerMenuCycle])
    }

    @SuppressLint("SetTextI18n")
    private fun updateStatMenu() {
        val statTextViews = listOf(
            R.id.statTextTopLeft,
            R.id.statTextTopRight,
            R.id.statTextBottomLeft,
            R.id.statTextBottomRight
        ).map { menuLayout.findViewById<TextView>(it).apply { text = null; background = null } }
        val energyBars = listOf(
            R.drawable.energy_bar_0, R.drawable.energy_bar_25, R.drawable.energy_bar_50,
            R.drawable.energy_bar_75, R.drawable.energy_bar_100)
        when (innerMenuCycle) {
            0 -> {
                statTextViews[0].text = "Level\n${user.mainDigitalMonster?.level}"
                statTextViews[1].text = "Stage\n${user.mainDigitalMonster?.digital_monster?.stage}"
                statTextViews[2].text = "Training\n${user.mainDigitalMonster?.trainings} / ${user.mainDigitalMonster?.maxTrainings}"
                statTextViews[3].text = "Battle\n${user.mainDigitalMonster?.wins} / ${user.mainDigitalMonster?.let { it.wins + it.losses }}"
            }
            1 -> {
                statTextViews[0].text = "Strength\n${user.mainDigitalMonster?.strength}"
                statTextViews[1].text = "Defense\n${user.mainDigitalMonster?.defense}"
                statTextViews[2].text = "Agility\n${user.mainDigitalMonster?.agility}"
                statTextViews[3].text = "Mind\n${user.mainDigitalMonster?.mind}"
            }
            2 -> {
                statTextViews[0].text = context.getString(R.string.hunger)
                statTextViews[1].setBackgroundResource(energyBars[user.mainDigitalMonster?.hunger!!])
                statTextViews[2].text = "Exercise"
                statTextViews[3].setBackgroundResource(energyBars[user.mainDigitalMonster?.exercise!!])
            }
            3 -> {
                statTextViews[0].text = "Energy"
                val energyIndex = ((user.mainDigitalMonster?.energy ?: 0) * 4 / (user.mainDigitalMonster?.maxEnergy ?: 1)).coerceIn(0, 4)
                statTextViews[1].setBackgroundResource(energyBars[energyIndex])
                statTextViews[2].text = "Evo Progress"
                statTextViews[3].setBackgroundResource(
                    energyBars[((user.mainDigitalMonster?.currentEvoPoints ?: 0) * 4 / (user.mainDigitalMonster?.digital_monster?.requiredEvoPoints ?: 1)).coerceIn(0, 4)]
                )
            }
        }
    }

    private fun selectEgg() {
        val name = menuLayout.findViewById<EditText>(R.id.editInput).text.toString()
        if(name.isEmpty() || name.length >12){
            displayMessage("name error")
        }
        else{
            val selectedDigitalMonsterId = user.eggs?.get(innerMenuCycle)?.id ?: return
            fetchService.createNewUserDigitalMonster(selectedDigitalMonsterId, name) { newUserDigitalMonster ->
                newUserDigitalMonster?.let { newMonster ->
                    CoroutineScope(Dispatchers.Main).launch {
                        user.mainDigitalMonster = newMonster
                        user.mainDigitalMonster!!.digital_monster.animation(mainImage, 1)
                        caseButtons[1].isClickable = true
                        cancel()
                    }
                }
            }
        }

    }

    private fun performAction(actionType: String) {
        user.mainDigitalMonster?.let { digitalMonster ->
            if (digitalMonster.digital_monster.stage == "Egg") {
                cancel()
                displayMessage("EGG not able to do this")
                return
            }

            if (isHandlerRunning) {
                stopAnimation(false)
                return
            }

            val (animationDuration, animationStep) = when (actionType) {
                "consumable", "cleaning" -> 5000L to 2
                else -> 10000L to 3
            }

            if (actionType == "consumable" || digitalMonster.energy > 0) {
                if (actionType != "consumable") {
                    digitalMonster.energy -= 1
                }
                startAnimation(animationStep, animationDuration, actionType)
            } else {
                displayMessage("No Energy")
            }
        }
    }

    private fun switchLight() {
        if(user.mainDigitalMonster!!.sleepStartedAt == null) {
            updateBackground(true)
            user.mainDigitalMonster!!.sleepStartedAt = Timestamp(System.currentTimeMillis() / 1000 * 1000).toString()
        }
        else {
            updateBackground(false)
            user.mainDigitalMonster!!.sleepStartedAt = null
        }
        cancel()
    }

    private fun updateBackground(isAsleep: Boolean) {
        val background: ConstraintLayout = caseBackground.findViewById(R.id.mainView)
        if(isAsleep) {
            background.setBackgroundResource(R.color.secondary)
            mainImage.visibility = View.INVISIBLE
        }
        else {
            background.setBackgroundResource(R.drawable.winterone)
            mainImage.visibility = View.VISIBLE
        }
    }

    @SuppressLint("SetTextI18n")
    private fun select(direction: Int) {
        if (isMenuOpen) {
            innerMenuCycle = (innerMenuCycle + direction + menuMax) % menuMax
            when (menuId) {
                -10 ->  updateMenuIcon()
                0 -> updateStatMenu()
                1 -> updateMenuIcon()
                2 -> updateMenuIcon()
                7 -> updateMenuIcon()
                8 -> updateMenuIcon()
            }
            val countTextView = menuLayout.findViewById<TextView>(R.id.count)
            countTextView.text = "${innerMenuCycle + 1} / $menuMax"
        }
        else {
            menuCycle = (menuCycle + direction + 8) % 8
            updateMenuImages()
        }
    }

    private fun cancel() {
        if(isMenuOpen) {
            isMenuOpen = false
            innerMenuCycle = 0
            menuId = -1
            menuLayout.visibility = View.GONE
            animationLayout.visibility = View.GONE
            if(user.mainDigitalMonster!!.sleepStartedAt == null) {
                mainImage.visibility = View.VISIBLE
            }
            if(isHandlerRunning) {
                stopAnimation(true)
            }
        }
        else {
            menuCycle = -1
            updateMenuImages()
        }
    }

    private fun accept() {
        if (!isMenuOpen && menuCycle != -1) {
            isMenuOpen = true
            menuId = menuCycle
            updateMenuLayout()
        } else {
            when (menuId) {
                -10 -> selectEgg()
                1 -> performAction("consumable")
                2 -> performAction("training")
                7 -> pullInPurchaseItems(innerMenuCycle)
            }
        }
    }

    private fun pullInPurchaseItems(type: Int) {
        var itemType = ""
        when(type){
            0 -> itemType = "case"
            1 -> itemType = "attack"
            2 -> itemType = "background"
            3 -> itemType = "consumable"

        }
        fetchService.fetchAndAttachItemsForSale(user, itemType, context) {
            if (user.itemsForSale != null) {
                menuId = 8
                innerMenuCycle = 0
                updateMenuLayout()
            }
            else{
                displayMessage("No Items")
                cancel()
                //Does not get here fix later maybe
            }
        }
    }

    private fun stopAnimation(calledFromCancel: Boolean) {
        if(!calledFromCancel) {
            if (isTraining) {
                user.useTrainingEquipment(user.getTrainingEquipment()[innerMenuCycle])
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
        animationLayout.findViewById<ImageView>(R.id.animationBarImageView).visibility = View.INVISIBLE
        if(animationType == "consumable") {
            val usedItem = user.getUsedItem(innerMenuCycle)
            usedItem.item.animation(animationLayout.findViewById(R.id.animationObjectImageView))
            fetchService.updateInventoryItem(usedItem)
        }
        else {
            val equipment = if (animationType == "training") {
                isTraining = true
                trainingEffort = 0
                animationLayout.findViewById<ImageView>(R.id.animationBarImageView).visibility = View.VISIBLE
                user.getTrainingEquipment()[innerMenuCycle]
            } else {
                user.getCleaningEquipment()
            }
            equipment?.trainingEquipment?.animation(animationLayout.findViewById(R.id.animationObjectImageView))
        }
        user.mainDigitalMonster!!.digital_monster.animation(animationLayout.findViewById(R.id.animationUserImageView), animationToPlay)
        isHandlerRunning = true
        val startTime = System.currentTimeMillis()
        runnable = object : Runnable {
            override fun run() {

                val currentTime = System.currentTimeMillis()
                val elapsedTime = currentTime - startTime
                if ( elapsedTime < animationTimer - 100L ) {
                    if( isTraining ) {
                        trainingEffort += 5
                        if (trainingEffort > 100) {
                            trainingEffort = 0
                        }
                        val effortImageView = animationLayout.findViewById<ImageView>(R.id.animationBarImageView)
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
        if(!isMenuOpen){
            isSettings = !isSettings
            setupImageResources()
        }
    }

    private fun displayMessage(message: String) {
        alertText.text = message
        alertText.visibility = View.VISIBLE
    }
}
