package thedigialex.digitalpet.controller

import android.annotation.SuppressLint
import android.content.Context
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.util.Log
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.Item
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.services.FetchService

class CaseController(private val context: Context, private val scope: CoroutineScope, private val parentLayout: ViewGroup, private val caseButtons: Array<Button>, private val menuImages: Array<ImageView>, private val user: User) {
    private var fetchService: FetchService = FetchService(context)
    private var menuCycle: Int = -1
    private var innerMenuCycle: Int = 0
    private var menuId: Int = -1
    private var menuMax: Int = 0
    private var isMenuOpen: Boolean = false
    private var isSettings = false
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)
    private var imageResources: MutableList<Bitmap> = mutableListOf()
    private var digitalMonsters: List<DigitalMonster>? = null
    private var imageView: ImageView? = null
    private var items: List<Item>? = null

    init {
        setupImageResources()
        setupButtons()
    }

    private fun setupButtons() {
        val actions = listOf(
            { accept() }, { cancel() }, { select(-1) }, { select(1) }, { switchMenu() }
        )
        caseButtons.forEachIndexed { index, button ->
            button.setOnClickListener { actions[index]() }
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

    private fun updateMenuImages() {
        menuImages.forEachIndexed { i, imageView ->
            imageView.setBackgroundResource(if (i == menuCycle) filledMenuImageResources[i] else emptyMenuImageResources[i])
        }
    }

    private fun updateMenuLayout() {
        parentLayout.visibility = View.VISIBLE
        val titleTextView = parentLayout.findViewById<TextView>(R.id.title)
        parentLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.GONE
        parentLayout.findViewById<ConstraintLayout>(R.id.statsViewLayout).visibility = View.GONE
        when (menuId) {
            -10 ->  {
                titleTextView.text = context.getString(R.string.select_egg)
                parentLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
            }
            0 -> {
                if (!isSettings) {
                    titleTextView.text = context.getString(R.string.stats)
                    menuMax = 4
                    parentLayout.findViewById<ConstraintLayout>(R.id.statsViewLayout).visibility = View.VISIBLE
                    updateStatMenu()
                } else {
                    titleTextView.text = "Settings 0"
                }
            }
            1 ->  titleTextView.text = if (!isSettings) "Accept 1" else "Settings 1"
            2 ->  titleTextView.text = if (!isSettings) "Accept 2" else "Settings 2"
            3 ->  titleTextView.text = if (!isSettings) "Accept 3" else "Settings 3"
            4 ->  titleTextView.text = if (!isSettings) "Accept 4" else "Settings 4"
            5 ->  titleTextView.text = if (!isSettings) "Accept 5" else "Settings 5"
            6 ->  titleTextView.text = if (!isSettings) "Accept 6" else "Settings 6"
            7 -> {
                if (!isSettings) {
                    titleTextView.text = "Shop"
                    menuMax = 4
                    parentLayout.findViewById<ImageView>(R.id.iconImage).visibility = View.VISIBLE
                    imageResources = mutableListOf(
                        BitmapFactory.decodeResource(context.resources, R.drawable.food_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.stat_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.train_menu_highlight),
                        BitmapFactory.decodeResource(context.resources, R.drawable.clean_menu_highlight)
                    )
                    updateMenuIcon()
                } else {
                    titleTextView.text = "Settings 7"
                }
            }
        }
        val countTextView = parentLayout.findViewById<TextView>(R.id.count)
        countTextView.text = "${innerMenuCycle + 1} / $menuMax"
    }

    @SuppressLint("SetTextI18n")
    private fun updateMenuIcon() {
        val  iconImage = parentLayout.findViewById<ImageView>(R.id.iconImage)
        iconImage.setImageBitmap(imageResources[innerMenuCycle])
    }

    private fun updateStatMenu() {
        val statTextViews = listOf(
            R.id.statTextTopLeft,
            R.id.statTextTopRight,
            R.id.statTextBottomLeft,
            R.id.statTextBottomRight
        ).map { parentLayout.findViewById<TextView>(it).apply { text = null; background = null } }
        val energyBars = listOf(
            R.drawable.energy_bar_0, R.drawable.energy_bar_25, R.drawable.energy_bar_50,
            R.drawable.energy_bar_75, R.drawable.energy_bar_100)
        when (innerMenuCycle) {
            0 -> {
                statTextViews[0].text = "Age\n${user.mainDigitalMonster?.age}"
                statTextViews[1].text = "Type\n${user.mainDigitalMonster?.type}"
                statTextViews[2].text = "Stage\n${user.mainDigitalMonster?.digital_monster?.stage}"
            }
            1 -> {
                statTextViews[0].text = "Level\n${user.mainDigitalMonster?.level}"
                statTextViews[1].text = "Battle\n${user.mainDigitalMonster?.wins} / ${user.mainDigitalMonster?.let { it.wins + it.losses }}"
                statTextViews[2].text = "Training\n${user.mainDigitalMonster?.trainings}"
            }
            2 -> {
                statTextViews[0].text = context.getString(R.string.hunger)
                statTextViews[1].setBackgroundResource(energyBars[user.mainDigitalMonster?.hunger!!])
                statTextViews[2].text = "Exercise"
                statTextViews[3].setBackgroundResource(energyBars[user.mainDigitalMonster?.exercise!!])
            }
            3 -> {
                statTextViews[0].text = "Energy"
                statTextViews[1].setBackgroundResource(energyBars[user.mainDigitalMonster?.energy!!/ user.mainDigitalMonster?.maxEnergy!!])
                statTextViews[2].text = "Evo Progress"
                statTextViews[3].setBackgroundResource(energyBars[user.mainDigitalMonster?.evoPoints!!/ user.mainDigitalMonster?.digital_monster?.requiredEvoPoints!!])
            }
        }
    }

    fun setUpNewUserDigitalMonster(digitalMonsters: List<DigitalMonster>, imageView: ImageView) {
        this.digitalMonsters = digitalMonsters
        this.imageView = imageView
        caseButtons[1].isClickable = false
        if (this.digitalMonsters?.isNotEmpty() == true) {
            val allSprites = mutableListOf<Bitmap>()
            this.digitalMonsters?.forEach { monster ->
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

    private fun pullInItems(type: String) {
        scope.launch {
            try {
                // Fetching remote items
                items = fetchService.fetchItems(type)

                // Log merged items
                items!!.forEach { item ->
                    Log.d("Item", "Merged item name: ${item.name}")
                }
            } catch (e: Exception) {
                Log.e("Error", "Error pulling items: ${e.message}")
            }
        }
    }

    private fun selectEgg() {
        val eggId = digitalMonsters?.get(innerMenuCycle)!!.eggId
        scope.launch {
            user.mainDigitalMonster = fetchService.createUserDigitalMonster(eggId)
            val digitalMonster = user.mainDigitalMonster?.digital_monster
            digitalMonster?.setupSprite(context) {
                imageView?.let {
                    digitalMonster.animation(it, 1)
                }
                caseButtons[1].isClickable = true
                cancel()
            }
        }
    }

    private fun select(direction: Int) {
        if (isMenuOpen) {            
            innerMenuCycle = (innerMenuCycle + direction + menuMax) % menuMax
            when (menuId) {
                -10 ->  updateMenuIcon()
                0 -> updateStatMenu()
                7 -> updateMenuIcon()
            }
            val countTextView = parentLayout.findViewById<TextView>(R.id.count)
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
            parentLayout.visibility = View.GONE
        }
        else {
            menuCycle = -1
            updateMenuImages()
        }
    }

    private fun accept() {
        if(!isMenuOpen && menuCycle != -1) {
            isMenuOpen = true
            menuId = menuCycle
            updateMenuLayout()
        }
        else {
            when (menuId) {
                -10 ->  selectEgg()
                7 -> when(innerMenuCycle) {
                    0 -> pullInItems("Usable")
                }
            }
        }
    }

    private fun switchMenu() {
        if(!isMenuOpen){
            isSettings = !isSettings
            setupImageResources()
        }
    }
}
