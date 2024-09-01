package thedigialex.digitalpet.controller

import android.annotation.SuppressLint
import android.content.Context
import android.graphics.Bitmap
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.services.FetchService

class CaseController(private val context: Context, private val scope: CoroutineScope, private val parentLayout: ViewGroup, private val caseButtons: Array<Button>, private val menuImages: Array<ImageView>, private val user: User) {
    private var fetchService: FetchService = FetchService(context)
    private var menuCycle: Int = -1
    private var innerMenuCycle: Int = 0
    private var menuId: Int = -1
    private var isMenuOpen: Boolean = false
    private var isSettings = false
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)
    private var imageResources: MutableList<Bitmap> = mutableListOf()
    private var digitalMonsters: List<DigitalMonster>? = null
    var imageView: ImageView? = null

    init {
        setupImageResources()
        setupButtons()
    }

    private fun setupButtons() {
        caseButtons[0].setOnClickListener { accept() }
        caseButtons[1].setOnClickListener { cancel() }
        caseButtons[2].setOnClickListener { select(-1) }
        caseButtons[3].setOnClickListener { select(1) }
        caseButtons[4].setOnClickListener { switchMenu() }
    }

    private fun setupImageResources() {
        emptyMenuImageResources[0] = if (!isSettings) R.drawable.stat_menu else R.drawable.stat_menu
        emptyMenuImageResources[1] = if (!isSettings) R.drawable.food_menu else R.drawable.stat_menu
        emptyMenuImageResources[2] = if (!isSettings) R.drawable.train_menu else R.drawable.stat_menu
        emptyMenuImageResources[3] = if (!isSettings) R.drawable.clean_menu else R.drawable.stat_menu
        emptyMenuImageResources[4] = if (!isSettings) R.drawable.light_menu else R.drawable.stat_menu
        emptyMenuImageResources[5] = if (!isSettings) R.drawable.battle_menu else R.drawable.stat_menu
        emptyMenuImageResources[6] = if (!isSettings) R.drawable.game_menu else R.drawable.stat_menu
        emptyMenuImageResources[7] = if (!isSettings) R.drawable.shop_menu else R.drawable.stat_menu
        filledMenuImageResources[0] = if (!isSettings) R.drawable.stat_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[1] = if (!isSettings) R.drawable.food_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[2] = if (!isSettings) R.drawable.train_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[3] = if (!isSettings) R.drawable.clean_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[4] = if (!isSettings) R.drawable.light_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[5] = if (!isSettings) R.drawable.battle_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[6] = if (!isSettings) R.drawable.game_menu_highlight else R.drawable.stat_menu_highlight
        filledMenuImageResources[7] = if (!isSettings) R.drawable.shop_menu_highlight else R.drawable.stat_menu_highlight
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
        when (menuId) {
            -10 ->  titleTextView.text = "Select Egg"
            0 ->  titleTextView.text =  if (!isSettings) "Accept 0" else "Settings 0"
            1 ->  titleTextView.text = if (!isSettings) "Accept 1" else "Settings 1"
            2 ->  titleTextView.text = if (!isSettings) "Accept 2" else "Settings 2"
            3 ->  titleTextView.text = if (!isSettings) "Accept 3" else "Settings 3"
            4 ->  titleTextView.text = if (!isSettings) "Accept 4" else "Settings 4"
            5 ->  titleTextView.text = if (!isSettings) "Accept 5" else "Settings 5"
            6 ->  titleTextView.text = if (!isSettings) "Accept 6" else "Settings 6"
            7 ->  titleTextView.text = if (!isSettings) "Accept 7" else "Settings 7"
        }
        updateMenuImage()
    }

    @SuppressLint("SetTextI18n")
    private  fun updateMenuImage() {
        val  iconImage = parentLayout.findViewById<ImageView>(R.id.iconImage)
        iconImage.setImageBitmap(imageResources[innerMenuCycle])
        val countTextView = parentLayout.findViewById<TextView>(R.id.count)
        countTextView.text = "${innerMenuCycle + 1} / ${imageResources.size}"
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
            }
            imageResources = allSprites
            menuId = -10
            isMenuOpen = true
            updateMenuLayout()
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
            innerMenuCycle = (innerMenuCycle + direction + imageResources.size) % imageResources.size
            updateMenuImage()
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
